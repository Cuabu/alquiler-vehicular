<?php
// Configuración de conexión a la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = "";
$bd       = "alquiler";

$conexion = new mysqli($servidor, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";
$tipo_alerta = "";

// Procesar el formulario al enviar por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id    = intval($_POST['cliente_id']);
    $vehiculo_id   = intval($_POST['vehiculo_id']);
    $fecha_inicio  = $_POST['fecha_inicio'];
    $fecha_fin     = $_POST['fecha_fin'];
    $valor_dia     = floatval($_POST['valor_dia']);
    $deposito      = floatval($_POST['deposito']);
    $estado        = $_POST['estado'];
    $observaciones = trim($_POST['observaciones']);

    if ($cliente_id > 0 && $vehiculo_id > 0 && !empty($fecha_inicio) && !empty($fecha_fin) && $valor_dia > 0) {
        
        // Calcular diferencia de días
        $dt_inicio = new DateTime($fecha_inicio);
        $dt_fin    = new DateTime($fecha_fin);
        $intervalo = $dt_inicio->diff($dt_fin);
        $dias      = $intervalo->days;
        
        // Si el alquiler es por el mismo día o menor a 24h, cobramos mínimo 1 día
        if ($dias == 0) {
            $dias = 1;
        }

        // Calcular el costo total del alquiler
        $total = ($dias * $valor_dia) + $deposito;

        // Iniciar transacción para garantizar integridad
        $conexion->begin_transaction();

        try {
            // 1. Insertar el registro en la tabla alquileres
            $sql_insert = "INSERT INTO alquileres (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, dias, valor_dia, deposito, total, estado, observaciones) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql_insert);
            $stmt->bind_param("iissidddss", $cliente_id, $vehiculo_id, $fecha_inicio, $fecha_fin, $dias, $valor_dia, $deposito, $total, $estado, $observaciones);
            $stmt->execute();
            $stmt->close();

            // 2. Si el alquiler entra como 'Activo', actualizar el vehículo a 'Alquilado'
            if ($estado === 'Activo') {
                $sql_update_vehiculo = "UPDATE vehiculo SET estado = 'Alquilado' WHERE id = ?";
                $stmt_upd = $conexion->prepare($sql_update_vehiculo);
                $stmt_upd->bind_param("i", $vehiculo_id);
                $stmt_upd->execute();
                $stmt_upd->close();
            }

            $conexion->commit();
            $mensaje = "¡Alquiler registrado con éxito! Total calculado para $dias día(s): <strong>$$total</strong>";
            $tipo_alerta = "success";

        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje = "Error al registrar el alquiler: " . $e->getMessage();
            $tipo_alerta = "danger";
        }

    } else {
        $mensaje = "Por favor, completa todos los campos obligatorios correctamente.";
        $tipo_alerta = "warning";
    }
}

// Consultar clientes activos para cargar el selector
$sql_usuarios = "SELECT id, nombre, apellido, numero_documento FROM usuarios WHERE estado = 'Activo' ORDER BY nombre ASC";
$res_clientes = $conexion->query($sql_usuarios);

// Consultar vehículos disponibles para cargar el selector
$sql_vehiculos = "SELECT id, placa, marca, modelo, valor_dia FROM vehiculos WHERE estado = 'Disponible' ORDER BY marca ASC";
$res_vehiculos = $conexion->query($sql_vehiculos);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alquiler - G00gle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="../administradores/index.php">🚗 G00gle - Gestión de Alquileres</a>
        <a href="../administradores/index.php" class="btn btn-outline-light btn-sm">Volver al Panel</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_alerta; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-center">Nuevo Registro de Alquiler</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="registrar_alquiler.php" method="POST">
                        
                        <div class="row">
                            <!-- Selección de Cliente -->
                            <div class="col-md-6 mb-3">
                                <label for="usuario_id" class="form-label fw-bold">Cliente *</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccione un cliente...</option>
                                    <?php while ($sql_usuarios = $res_clientes->fetch_assoc()): ?>
                                        <option value="<?php echo $sql_usuarios['id']; ?>">
                                            <?php echo htmlspecialchars($sql_usuarios['nombre'] . " " . $sql_usuarios['apellido'] . " (" . $sql_usuarios['numero_documento'] . ")"); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Selección de Vehículo -->
                            <div class="col-md-6 mb-3">
                                <label for="vehiculo_id" class="form-label fw-bold">Vehículo Disponible *</label>
                                <select class="form-select" id="vehiculo_id" name="vehiculo_id" required onchange="actualizarPrecio()">
                                    <option value="" data-precio="0">Seleccione un vehículo...</option>
                                    <?php while ($vehiculo = $res_vehiculo->fetch_assoc()): ?>
                                        <option value="<?php echo $vehiculo['id']; ?>" data-precio="<?php echo $vehiculo['valor_dia']; ?>">
                                            <?php echo htmlspecialchars($vehiculo['placa'] . " - " . $vehiculo['marca'] . " " . $vehiculo['modelo'] . " ($" . $vehiculo['valor_dia'] . "/día)"); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fecha de Inicio -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label fw-bold">Fecha y Hora de Inicio *</label>
                                <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                            </div>

                            <!-- Fecha de Fin -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label fw-bold">Fecha y Hora de Fin *</label>
                                <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Valor por Día -->
                            <div class="col-md-4 mb-3">
                                <label for="valor_dia" class="form-label fw-bold">Valor por Día ($) *</label>
                                <input type="number" step="0.01" class="form-control" id="valor_dia" name="valor_dia" placeholder="0.00" required>
                                <div class="form-text">Se llena al elegir vehículo.</div>
                            </div>

                            <!-- Depósito / Garantía -->
                            <div class="col-md-4 mb-3">
                                <label for="deposito" class="form-label fw-bold">Depósito / Garantía ($)</label>
                                <input type="number" step="0.01" class="form-control" id="deposito" name="deposito" value="0.00" required>
                            </div>

                            <!-- Estado Inicial -->
                            <div class="col-md-4 mb-3">
                                <label for="estado" class="form-label fw-bold">Estado del Alquiler *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Reservado">Reservado</option>
                                    <option value="Activo" selected>Activo (Inicia de inmediato)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label fw-bold">Observaciones adicionales</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Estado del vehículo al entregarlo, condiciones de combustible, etc..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2">🚗 Registrar Alquiler</button>
                            <a href="../index.php" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<footer class="text-center mt-auto mb-4 text-secondary">
    © <?php echo date("Y"); ?> G00gle - Proyecto Académico
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pequeño script JS para autocompletar el valor por día al elegir el vehículo en el select
    function actualizarPrecio() {
        const selectVehiculo = document.getElementById('vehiculo_id');
        const opcionSeleccionada = selectVehiculo.options[selectVehiculo.selectedIndex];
        const precio = opcionSeleccionada.getAttribute('data-precio') || 0;
        document.getElementById('valor_dia').value = precio;
    }
</script>
</body>
</html>
<?php
$conexion->close();
?>