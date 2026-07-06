<?php
// Configuración de conexión a la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = "";
$bd       = "alquiler";

$conexion = new mysqli($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";
$tipo_alerta = "";

// Procesar formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa  = trim($_POST['placa']);
    $valor  = floatval($_POST['valor']);
    $metodo = $_POST['metodo'];

    if (!empty($placa) && $valor > 0 && !empty($metodo)) {
        
        // 1. Subida y tratamiento de la imagen (Comprobante)
        $comprobante_pago = "";

        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            $nombre_temporal = $_FILES['comprobante']['tmp_name'];
            $nombre_original = $_FILES['comprobante']['name'];
            $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

            $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($extension, $extensiones_permitidas)) {
                
                // Crear carpeta si no existe
                $directorio = "comprobantes/";
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                // Generar nombre único corto para no exceder VARCHAR(50)
                // Ejemplo: pag_1720226000.jpg (aprox. 18 caracteres)
                $nombre_archivo = "pag_" . time() . "." . $extension;
                $ruta_destino = $directorio . $nombre_archivo;

                if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                    $comprobante_pago = $nombre_archivo;
                } else {
                    $mensaje = "Error al guardar el archivo de imagen en el servidor.";
                    $tipo_alerta = "danger";
                }
            } else {
                $mensaje = "Formato de imagen inválido. Solo se permiten archivos JPG, PNG o WEBP.";
                $tipo_alerta = "danger";
            }
        } else {
            $comprobante_pago = "Sin imagen";
        }

        // 2. Si la imagen se procesó bien, buscamos el alquiler y registramos el pago
        if (empty($mensaje)) {
            // Buscar el alquiler activo para esa placa
            $sql_buscar = "SELECT a.id AS alquiler_id 
                           FROM alquileres a 
                           INNER JOIN vehiculos v ON a.vehiculo_id = v.id 
                           WHERE v.placa = ? AND a.estado = 'Activo' 
                           LIMIT 1";
            
            $stmt_buscar = $conexion->prepare($sql_buscar);
            $stmt_buscar->bind_param("s", $placa);
            $stmt_buscar->execute();
            $resultado = $stmt_buscar->get_result();

            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                $alquiler_id = $fila['alquiler_id'];
                $stmt_buscar->close();

                // Insertar en la tabla pagos respetando todas las columnas exactas
                $sql_insertar = "INSERT INTO pagos (alquiler_id, metodo, valor, placa, comprobante_pago) 
                                 VALUES (?, ?, ?, ?, ?)";
                $stmt_insertar = $conexion->prepare($sql_insertar);
                $stmt_insertar->bind_param("isdss", $alquiler_id, $metodo, $valor, $placa, $comprobante_pago);

                if ($stmt_insertar->execute()) {
                    $mensaje = "¡Pago registrado exitosamente para el vehículo con placa <strong>" . htmlspecialchars($placa) . "</strong>!";
                    $tipo_alerta = "success";
                } else {
                    $mensaje = "Error al guardar en la base de datos: " . $conexion->error;
                    $tipo_alerta = "danger";
                }
                $stmt_insertar->close();

            } else {
                $mensaje = "No se encontró ningún alquiler con estado <strong>Activo</strong> para la placa: " . htmlspecialchars($placa);
                $tipo_alerta = "danger";
                $stmt_buscar->close();
            }
        }

    } else {
        $mensaje = "Por favor, completa correctamente todos los campos obligatorios.";
        $tipo_alerta = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pago - G00gle</title>
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
        <a class="navbar-brand" href="../index.php">💳 G00gle - Gestión de Pagos</a>
        <a href="../index.php" class="btn btn-outline-light btn-sm">Volver al Panel</a>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_alerta; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-center">Formulario de Registro de Pago</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="registrar.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="placa" class="form-label fw-bold">Placa del Vehículo *</label>
                            <input type="text" class="form-control" id="placa" name="placa" placeholder="Ej. ABC123" required>
                            <div class="form-text">El sistema buscará el alquiler activo asociado a esta placa.</div>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label fw-bold">Valor a Pagar ($) *</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label for="metodo" class="form-label fw-bold">Método de Pago *</label>
                            <select class="form-select" id="metodo" name="metodo" required>
                                <option value="">Seleccione una opción...</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="comprobante" class="form-label fw-bold">Comprobante de Pago (Imagen) *</label>
                            <input type="file" class="form-control" id="comprobante" name="comprobante" accept="image/png, image/jpeg, image/jpg, image/webp" required>
                            <div class="form-text">Sube la captura de la transferencia o factura (JPG, PNG, WEBP). Se guardará en la columna 'comprobante_pago'.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold py-2">💾 Guardar Pago</button>
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
</body>
</html>
<?php
$conexion->close();
?>