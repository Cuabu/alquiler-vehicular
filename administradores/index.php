<?php
// index.php
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G00gle - Panel Principal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-light: #f4f7f6;
            --brand-primary: #0d6efd;
            --brand-gradient: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .hero {
            background: var(--brand-gradient);
            color: white;
            padding: 80px 20px 120px;
            text-align: center;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
        }

        .dashboard-container {
            margin-top: -60px; /* Superpone las tarjetas al hero */
            z-index: 10;
            position: relative;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: white;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .icon-wrapper {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .card-title {
            font-weight: 600;
            color: #2b3a4a;
        }
        
        .btn-custom-rounded {
            border-radius: 50rem;
            padding: 8px 25px;
            font-weight: 500;
        }
    </style>
</head>

<body>

<!-- Barra de Navegación Superior -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">
            <i class="bi bi-grid-1x2-fill me-2"></i> G00gle Panel
        </a>
        <div class="d-flex">
            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="window.location='../logout.php'">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
            </button>
        </div>
    </div>
</nav>

<!-- Encabezado / Hero -->
<header class="hero">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Bienvenido</h1>
        <p class="lead opacity-75">Sistema Web de Administración</p>
    </div>
</header>

<!-- Contenido Principal -->
<main class="container dashboard-container mb-5">
    <div class="row g-4 justify-content-center">

        <!-- Tarjeta: Alquiler Vehicular -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-2">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-wrapper text-primary">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <h4 class="card-title">Alquiler Vehicular</h4>
                        <p class="text-muted small">
                            Administración de vehículos, clientes, alquileres y reportes.
                        </p>
                    </div>
                    <a href="../alquileres/registrar_alquiler.php" class="btn btn-primary btn-custom-rounded mt-3">
                        Ingresar
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Base de Datos -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-2">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-wrapper text-success">
                            <i class="bi bi-database-fill"></i>
                        </div>
                        <h4 class="card-title">Base de Datos</h4>
                        <p class="text-muted small">
                            Acceso a phpMyAdmin para fines académicos y de administración.
                        </p>
                    </div>
                    <a href="http://localhost/phpmyadmin/" class="btn btn-success btn-custom-rounded mt-3" target="_blank">
                        Abrir phpMyAdmin
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Sitio Web -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-2">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-wrapper text-dark">
                            <i class="bi bi-globe"></i>
                        </div>
                        <h4 class="card-title">Sitio Web</h4>
                        <p class="text-muted small">
                            Acceso al sitio publicado mediante Ngrok.
                        </p>
                    </div>
                    <a href="https://present-divine-cub.ngrok-free.app" class="btn btn-dark btn-custom-rounded mt-3" target="_blank">
                        Abrir Sitio
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Registro de Conductores -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-2">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-wrapper text-info">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>
                        <h4 class="card-title">Registro en el Sistema</h4>
                        <p class="text-muted small">
                            Registro de conductores para el sistema de alquiler vehicular.
                        </p>
                    </div>
                    <a href="login.php" class="btn btn-info text-white btn-custom-rounded mt-3">
                        Registrar Conductor
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta: Registrar Pago -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-2">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-wrapper text-warning">
                            <i class="bi bi-credit-card-fill"></i>
                        </div>
                        <h4 class="card-title">Registrar Pago</h4>
                        <p class="text-muted small">
                            Gestión y registro de pagos correspondientes a los alquileres.
                        </p>
                    </div>
                    <a href="../pagos/registrar.php" class="btn btn-warning text-dark btn-custom-rounded mt-3">
                        Registrar Pago
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<footer class="text-center mt-5 mb-4 text-secondary">
    <small>© <?php echo date("Y"); ?> G00gle - Proyecto Académico</small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>