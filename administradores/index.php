<?php
// index.php
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G00gle - Panel Principal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .hero {
            background: #0d6efd;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .card {
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
        }
    </style>
</head>

<body>

<div class="hero">
    <h1>Bienvenido</h1>
    <p class="lead">
        Sistema Web de Administración
    </p>
    <button class="btn btn-danger"
        onclick="window.location='../logout.php'">
    Cerrar sesión
</button>
</div>

<div class="container mt-5">
    <div class="row g-4 justify-content-center">

        <!-- Tarjeta: Alquiler Vehicular -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h3>🚗</h3>
                        <h4>Alquiler Vehicular</h4>
                        <p class="text-muted">
                            Administración de vehículos, clientes, alquileres y reportes.
                        </p>
                    </div>
                    <a href="../alquileres/registrar_alquiler.php" class="btn btn-primary mt-3">
                        Ingresar
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Base de Datos -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h3>🗄</h3>
                        <h4>Base de Datos</h4>
                        <p class="text-muted">
                            Acceso a phpMyAdmin para fines académicos y de administración.
                        </p>
                    </div>
                    <a href="http://localhost/phpmyadmin/" class="btn btn-success mt-3" target="_blank">
                        Abrir phpMyAdmin
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Sitio Web -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h3>🌐</h3>
                        <h4>Sitio Web</h4>
                        <p class="text-muted">
                            Acceso al sitio publicado mediante Ngrok.
                        </p>
                    </div>
                    <a href="https://present-divine-cub.ngrok-free.app" class="btn btn-dark mt-3" target="_blank">
                        Abrir Sitio
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Registro de Conductores -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h3>👤</h3>
                        <h4>Registro en el Sistema</h4>
                        <p class="text-muted">
                            Registro de conductores para el sistema de alquiler vehicular.
                        </p>
                    </div>
                    <a href="login.php" class="btn btn-info text-white mt-3">
                        Registrar Conductor
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Registrar Pago -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h3>💳</h3>
                        <h4>Registrar Pago</h4>
                        <p class="text-muted">
                            Gestión y registro de pagos correspondientes a los alquileres.
                        </p>
                    </div>
                    <a href="../pagos/registrar.php" class="btn btn-warning mt-3">
                        Registrar Pago
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center mt-5 mb-4 text-secondary">
    © <?php echo date("Y"); ?> G00gle - Proyecto Académico
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>