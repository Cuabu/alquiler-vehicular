<?php
session_start();

// Si el usuario ya inició sesión, redirigir al dashboard
if (isset($_SESSION['id'])) {
    header("Location: ./administradores/index.php");
    exit;
}

require "./config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario  = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    if (!empty($usuario) && !empty($password)) {

        // Consulta preparada segura utilizando mysqli
        $sql = $conexion->prepare("SELECT * FROM administradores WHERE usuario = ? AND estado = 'Activo' LIMIT 1");
        $sql->bind_param("s", $usuario);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows === 1) {

            $datos = $resultado->fetch_assoc();

            // Verificar contraseña (hash o texto plano para migración)
$loginCorrecto = false;

if (password_verify($password, $datos["password"])) {

    // La contraseña ya está cifrada
    $loginCorrecto = true;

} elseif ($password === $datos["password"]) {

    // Contraseña antigua en texto plano
    $loginCorrecto = true;

    // Actualizar automáticamente a password_hash
    $nuevoHash = password_hash($password, PASSWORD_DEFAULT);

    $updatePass = $conexion->prepare(
        "UPDATE administradores SET password = ? WHERE id = ?"
    );

    $updatePass->bind_param("si", $nuevoHash, $datos["id"]);
    $updatePass->execute();
    $updatePass->close();
}

if ($loginCorrecto) {

    // Actualizar último acceso
    $actualizar = $conexion->prepare(
        "UPDATE administradores
         SET ultimo_acceso = NOW()
         WHERE id = ?"
    );

    $actualizar->bind_param("i", $datos["id"]);
    $actualizar->execute();
    $actualizar->close();

    session_regenerate_id(true);

    $_SESSION["id"]       = $datos["id"];
    $_SESSION["nombre"]   = $datos["nombre"];
    $_SESSION["apellido"] = $datos["apellido"];
    $_SESSION["usuario"]  = $datos["usuario"];
    $_SESSION["correo"]   = $datos["correo"];
    $_SESSION["rol"]      = $datos["rol"];

    header("Location: ./administradores/index.php");
    exit;

} else {

    $mensaje = '
    <div class="alert alert-danger alert-dismissible fade show text-center py-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Usuario o contraseña incorrectos.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

            } else {
                // Mensaje genérico por seguridad
                $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show text-center py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Credenciales incorrectas.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }

        } else {
            // Mensaje genérico por seguridad (evita confirmar si el usuario existe)
            $mensaje = '
            <div class="alert alert-danger alert-dismissible fade show text-center py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Credenciales incorrectas o cuenta inactiva.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }

        $sql->close();

    } else {
        $mensaje = '
        <div class="alert alert-warning alert-dismissible fade show text-center py-2" role="alert">
            <i class="bi bi-info-circle-fill me-1"></i> Complete todos los campos.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - Gestión Vehicular</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
        }

        .logo {
            font-size: 65px;
            color: #0d6efd;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="card">
                    <div class="card-body p-4 p-sm-5">

                        <div class="text-center mb-4">
                            <i class="bi bi-car-front-fill logo d-inline-block"></i>
                            <h3 class="mt-2 fw-bold text-dark">Administración</h3>
                            <p class="text-muted mb-0">Acceso al Panel de Administración</p>
                        </div>

                        <?= $mensaje ?>

                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                            </button>
                        </form>

                        <hr class="my-4 text-muted">

                        <div class="text-center text-secondary small">
                            © <?= date("Y") ?> Sistema de Gestión de Vehículos
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>