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
                    $mensaje = "<i class='bi bi-check-circle-fill me-2'></i> ¡Pago registrado exitosamente para el vehículo con placa <strong>" . htmlspecialchars($placa) . "</strong>!";
                    $tipo_alerta = "success";
                } else {
                    $mensaje = "Error al guardar en la base de datos: " . $conexion->error;
                    $tipo_alerta = "danger";
                }
                $stmt_insertar->close();

            } else {
                $mensaje = "<i class='bi bi-exclamation-triangle-fill me-2'></i> No se encontró ningún alquiler con estado <strong>Activo</strong> para la placa: " . htmlspecialchars($placa);
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

        /* Fuerza las letras a mostrarse en mayúsculas en el input */
        #placa {
            text-transform: uppercase;
        }

        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
        }

        /* Contenedor de previsualización */
        #preview-container {
            display: none;
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<!-- Barra de Navegación Superior -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="../index.php">
            <i class="bi bi-grid-1x2-fill me-2"></i> G00gle Panel
        </a>
        <div class="d-flex">
            <a href="../index.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Volver al Panel
            </a>
        </div>
    </div>
</nav>

<!-- Encabezado de la sección -->
<header class="hero-mini">
    <div class="container">
        <h2 class="fw-bold mb-0"><i class="bi bi-credit-card-2-front-fill me-2"></i>Gestión de Pagos</h2>
    </div>
</header>

<!-- Contenedor del Formulario -->
<main class="container form-container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <!-- Mensajes de Alerta -->
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_alerta; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div><?php echo $mensaje; ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Tarjeta Principal del Formulario -->
            <div class="card">
                <div class="card-header bg-white py-3 text-center">
                    <h5 class="mb-0 fw-bold text-secondary">Registrar Nuevo Pago</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="registrar.php" method="POST" enctype="multipart/form-data">
                        
                        <!-- Campo: Placa -->
                        <div class="mb-3">
                            <label for="placa" class="form-label fw-semibold text-dark">Placa del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placa" name="placa" placeholder="Ej. ABC123" required maxlength="10">
                            <div class="form-text text-muted">El sistema buscará el alquiler activo asociado a esta placa.</div>
                        </div>

                        <!-- Campo: Valor -->
                        <div class="mb-3">
                            <label for="valor" class="form-label fw-semibold text-dark">Valor a Pagar ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">$</span>
                                <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="0.00" min="0.01" required>
                            </div>
                        </div>

                        <!-- Campo: Método -->
                        <div class="mb-3">
                            <label for="metodo" class="form-label fw-semibold text-dark">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select" id="metodo" name="metodo" required>
                                <option value="">Seleccione una opción...</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                            </select>
                        </div>

                        <!-- Campo: Comprobante (Imagen) -->
                        <div class="mb-4">
                            <label for="comprobante" class="form-label fw-semibold text-dark">Comprobante de Pago (Imagen) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="comprobante" name="comprobante" accept="image/png, image/jpeg, image/jpg, image/webp" required>
                            <div class="form-text text-muted mb-3">Formatos admitidos: JPG, PNG, WEBP.</div>
                            
                            <!-- Área de previsualización dinámica -->
                            <div id="preview-container" class="text-center p-2 bg-light">
                                <p class="small text-muted mb-1 fw-bold">Vista previa del comprobante:</p>
                                <img id="image-preview" src="#" alt="Vista previa" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning text-dark fw-bold btn-custom shadow-sm">
                                <i class="bi bi-floppy-fill me-2"></i> Guardar Pago
                            </button>
                            <a href="../index.php" class="btn btn-light btn-custom border">Cancelar</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<!-- Pie de Página -->
<footer class="text-center mt-auto mb-4 text-secondary">
    <small>© <?php echo date("Y"); ?> G00gle - Proyecto Académico</small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para previsualizar la imagen cargada en tiempo real -->
<script>
    document.getElementById('comprobante').addEventListener('change', function(e) {
        const reader = new FileReader();
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');

        if(e.target.files[0]) {
            reader.readAsDataURL(e.target.files[0]);
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>

</body>
</html>
<?php
$conexion->close();
?>