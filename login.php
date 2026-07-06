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
                    "UPDATE administradores SET ultimo_acceso = NOW() WHERE id = ?"
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
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - Gestión Vehicular</title>
    
    <!-- Bootstrap 5.3.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .card-login {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            background: #ffffff;
        }

        .brand-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: #e7f1ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-login:hover .brand-icon-wrapper {
            transform: scale(1.08) rotate(3deg);
        }

        .logo {
            font-size: 38px;
            color: #0d6efd;
        }

        /* Estilización premium de campos de texto */
        .input-group-custom {
            position: relative;
        }

        .input-group-custom .form-control {
            padding-left: 2.75rem;
            border-radius: 0.75rem;
            height: 48px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        .input-group-custom .form-control:focus {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }

        .input-group-custom .form-control:focus ~ .input-icon {
            color: #0d6efd;
        }

        .btn-submit {
            height: 48px;
            border-radius: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.25);
        }

        /* Evita el salto de línea feo en la palabra Administración */
        .title-panel {
            white-space: nowrap;
            font-size: 1.6rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4">

                <div class="card card-login p-3 p-sm-4">
                    <div class="card-body">

                        <!-- Encabezado Limpio -->
                        <div class="text-center mb-4">
                            <div class="brand-icon-wrapper">
                                <i class="bi bi-car-front-fill logo"></i>
                            </div>
                            <h2 class="fw-bold text-dark title-panel mb-1">Administración</h2>
                            <p class="text-muted small">Acceso al Panel de Control</p>
                        </div>

                        <!-- Contenedor de Alertas Dinámicas -->
                        <div class="mb-3">
                            <?= $mensaje ?>
                        </div>

                        <!-- Formulario -->
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label class="form-label fw-medium text-secondary small">Usuario</label>
                                <div class="input-group-custom">
                                    <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario" required autofocus>
                                    <i class="bi bi-person input-icon"></i>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium text-secondary small">Contraseña</label>
                                <div class="input-group-custom">
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                    <i class="bi bi-lock input-icon"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-submit w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-in-right fs-5"></i> Ingresar al Sistema
                            </button>
                        </form>

                        <div class="text-center mt-5 text-muted small border-top pt-3 opacity-75">
                            &copy; <?= date("Y") ?> Sistema de Gestión Vehicular
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>