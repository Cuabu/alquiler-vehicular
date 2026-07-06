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
                $sql_update_vehiculo = "UPDATE vehiculos SET estado = 'Alquilado' WHERE id = ?";
                $stmt_upd = $conexion->prepare($sql_update_vehiculo);
                $stmt_upd->bind_param("i", $vehiculo_id);
                $stmt_upd->execute();
                $stmt_upd->close();
            }

            $conexion->commit();
            $mensaje = "<i class='bi bi-check-circle-fill me-2'></i> ¡Alquiler registrado con éxito! Total calculado para $dias día(s): <strong>$$total</strong>";
            $tipo_alerta = "success";

        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje = "<i class='bi bi-exclamation-triangle-fill me-2'></i> Error al registrar el alquiler: " . $e->getMessage();
            $tipo_alerta = "danger";
        }

    } else {
        $mensaje = "Por favor, completa todos los campos obligatorios correctamente.";
        $tipo_alerta = "warning";
    }
}

// Consultar clientes activos para cargar el selector
$sql_usuarios_query = "SELECT id, nombre, apellido, numero_documento FROM usuarios WHERE estado = 'Activo' ORDER BY nombre ASC";
$res_clientes = $conexion->query($sql_usuarios_query);

// Consultar vehículos disponibles para cargar el selector
$sql_vehiculos_query = "SELECT id, placa, marca, modelo, valor_dia FROM vehiculos WHERE estado = 'Disponible' ORDER BY marca ASC";
$res_vehiculos = $conexion->query($sql_vehiculos_query);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alquiler - G00gle</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: #f4f7f6;
            --brand-gradient: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .hero-mini {
            background: var(--brand-gradient);
            color: white;
            padding: 40px 20px 80px;
            text-align: center;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .form-container {
            margin-top: -50px;
            z-index: 10;
            position: relative;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            background: white;
        }

        .card-header {
            border-bottom: 1px solid #edf2f7;
            font-weight: 600;
            color: #2b3a4a;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #ced4da;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        }

        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .resumen-caja {
            background-color: #e9ecef;
            border-radius: 12px;
            padding: 15px;
            border: 1px dashed #adb5bd;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="../administradores/index.php">
            <i class="bi bi-grid-1x2-fill me-2"></i> G00gle Panel
        </a>
        <div class="d-flex">
            <a href="../administradores/index.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Volver al Panel
            </a>
        </div>
    </div>
</nav>

<!-- Encabezado -->
<header class="hero-mini">
    <div class="container">
        <h2 class="fw-bold mb-0"><i class="bi bi-car-front-fill me-2"></i>Gestión de Alquileres</h2>
    </div>
</header>

<!-- Contenedor del Formulario -->
<main class="container form-container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_alerta; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div><?php echo $mensaje; ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-white py-3 text-center">
                    <h5 class="mb-0 fw-bold text-secondary">Nuevo Registro de Alquiler</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="registrar_alquiler.php" method="POST" id="formAlquiler">
                        
                        <div class="row">
                            <!-- Selección de Cliente -->
                            <div class="col-md-6 mb-3">
                                <label for="cliente_id" class="form-label fw-semibold text-dark"><i class="bi bi-person-fill me-1"></i> Cliente <span class="text-danger">*</span></label>
                                <!-- CORRECCIÓN: name="cliente_id" para que coincida con el backend POST -->
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccione un cliente...</option>
                                    <?php while ($cliente = $res_clientes->fetch_assoc()): ?>
                                        <option value="<?php echo $cliente['id']; ?>">
                                            <?php echo htmlspecialchars($cliente['nombre'] . " " . $cliente['apellido'] . " (" . $cliente['numero_documento'] . ")"); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Selección de Vehículo -->
                            <div class="col-md-6 mb-3">
                                <label for="vehiculo_id" class="form-label fw-semibold text-dark"><i class="bi bi-key-fill me-1"></i> Vehículo Disponible <span class="text-danger">*</span></label>
                                <!-- CORRECCIÓN: el bucle usa $vehiculo y lee de $res_vehiculos -->
                                <select class="form-select" id="vehiculo_id" name="vehiculo_id" required onchange="calcularTotal()">
                                    <option value="" data-precio="0">Seleccione un vehículo...</option>
                                    <?php while ($vehiculo = $res_vehiculos->fetch_assoc()): ?>
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
                                <label for="fecha_inicio" class="form-label fw-semibold text-dark"><i class="bi bi-calendar-event me-1"></i> Inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d\TH:i'); ?>" required onchange="calcularTotal()">
                            </div>

                            <!-- Fecha de Fin -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label fw-semibold text-dark"><i class="bi bi-calendar-check me-1"></i> Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required onchange="calcularTotal()">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Valor por Día -->
                            <div class="col-md-4 mb-3">
                                <label for="valor_dia" class="form-label fw-semibold text-dark">Valor Día ($) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" class="form-control" id="valor_dia" name="valor_dia" placeholder="0.00" readonly required onchange="calcularTotal()">
                                </div>
                                <div class="form-text text-muted small">Se asigna automáticamente.</div>
                            </div>

                            <!-- Depósito / Garantía -->
                            <div class="col-md-4 mb-3">
                                <label for="deposito" class="form-label fw-semibold text-dark">Depósito ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" class="form-control" id="deposito" name="deposito" value="0.00" required oninput="calcularTotal()">
                                </div>
                            </div>

                            <!-- Estado Inicial -->
                            <div class="col-md-4 mb-3">
                                <label for="estado" class="form-label fw-semibold text-dark">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Reservado">Reservado</option>
                                    <option value="Activo" selected>Activo (Inicia ya)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label fw-semibold text-dark"><i class="bi bi-card-text me-1"></i> Observaciones adicionales</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Detalles del vehículo al entregar, rayones, gasolina..."></textarea>
                        </div>

                        <!-- Caja de Resumen Dinámico -->
                        <div class="resumen-caja mb-4 text-center">
                            <h6 class="text-muted mb-1">Total Estimado del Alquiler</h6>
                            <h3 class="text-primary fw-bold mb-0" id="total_preview">$0.00</h3>
                            <small class="text-secondary" id="dias_preview">0 días calculados</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2 btn-custom shadow-sm">
                                <i class="bi bi-car-front-fill me-2"></i> Registrar Alquiler
                            </button>
                            <a href="../administradores/index.php" class="btn btn-light border btn-custom">Cancelar</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<footer class="text-center mt-auto mb-4 text-secondary">
    <small>© <?php echo date("Y"); ?> G00gle - Proyecto Académico</small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para actualizar precio y calcular el total en vivo -->
<script>
    function calcularTotal() {
        // 1. Obtener y setear el valor por día del vehículo
        const selectVehiculo = document.getElementById('vehiculo_id');
        const opcionSeleccionada = selectVehiculo.options[selectVehiculo.selectedIndex];
        const precioDia = parseFloat(opcionSeleccionada.getAttribute('data-precio')) || 0;
        document.getElementById('valor_dia').value = precioDia;

        // 2. Obtener fechas
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        let dias = 0;

        if (fechaInicio && fechaFin) {
            const date1 = new Date(fechaInicio);
            const date2 = new Date(fechaFin);
            
            // Diferencia en milisegundos a días
            const diffTime = Math.abs(date2 - date1);
            dias = Math.floor(diffTime / (1000 * 60 * 60 * 24)); 
            
            // Si es menor a 24 horas o el mismo día, cobrar mínimo 1 día (Igual a la lógica PHP)
            if (dias === 0 && date2 > date1) {
                dias = 1;
            }
        }

        // 3. Obtener depósito y calcular total
        const deposito = parseFloat(document.getElementById('deposito').value) || 0;
        const total = (dias * precioDia) + deposito;

        // 4. Mostrar en pantalla
        document.getElementById('dias_preview').innerText = `${dias} día(s) calculados`;
        
        // Formatear a moneda (ej. 1,500.00)
        document.getElementById('total_preview').innerText = "$" + total.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>
</body>
</html>
<?php
$conexion->close();
?>