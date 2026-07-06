<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Alquiler Vehicular</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts para una tipografía más moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #333333;
        }

        /* Navbar elegante */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Hero section con un gradiente sofisticado */
        .hero {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 80px 20px;
            border-radius: 0 0 2rem 2rem;
            box-shadow: 0 4px 20px rgba(13, 110, 253, 0.15);
        }

        /* Tarjetas estilo Dashboard moderno */
        .card-custom {
            border: none;
            border-radius: 1.25rem;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        }

        /* Contenedor estilizado para los iconos */
        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 35px;
            transition: transform 0.3s ease;
        }

        .card-custom:hover .icon-box {
            transform: scale(1.1);
        }

        .icon-bg-pago {
            background-color: #fff9db;
        }

        .icon-bg-sesion {
            background-color: #e7f1ff;
        }

        /* Botones estilizados */
        .btn-custom {
            border-radius: 0.75rem;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
    </style>
</head>

<body>

<!-- Navbar Superior -->
<nav class="navbar navbar-expand-lg py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">
            🚗 Alquiler Vehicular
        </a>
        <button class="btn btn-outline-danger btn-custom btn-sm" 
                onclick="window.location='logout.php'">
            Cerrar sesión
        </button>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero text-center mb-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Sistema de Alquiler Vehicular</h1>
        <p class="lead opacity-75 mb-0">
            Bienvenido al panel de gestión. Seleccione la acción que desea realizar a continuación.
        </p>
    </div>
</div>

<!-- Contenido Principal / Tarjetas -->
<div class="container mb-5">
    <div class="row justify-content-center g-4">

        <!-- Registrar Pago -->
        <div class="col-md-5 col-lg-4">
            <div class="card card-custom h-100 p-4">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-box icon-bg-pago">💳</div>
                        <h4 class="fw-bold mb-3">Registrar Pago</h4>
                        <p class="text-muted small mb-4">
                            Realice el registro de pagos correspondientes a los alquileres activos de forma rápida y segura.
                        </p>
                    </div>
                    <a href="./pagos/registrar.php" class="btn btn-warning text-dark w-100 btn-custom fw-semibold">
                        Registrar Pago
                    </a>
                </div>
            </div>
        </div>

        <!-- Iniciar Sesión -->
        <div class="col-md-5 col-lg-4">
            <div class="card card-custom h-100 p-4">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-box icon-bg-sesion">🔐</div>
                        <h4 class="fw-bold mb-3">Iniciar Sesión</h4>
                        <p class="text-muted small mb-4">
                            Acceda al sistema administrativo con sus credenciales autorizadas de usuario y contraseña.
                        </p>
                    </div>
                    <a href="login.php" class="btn btn-primary w-100 btn-custom">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer class="text-center py-4 bg-white border-top text-muted mt-auto">
    <div class="container">
        <small>© <?php echo date("Y"); ?> Sistema de Alquiler Vehicular. Todos los derechos reservados.</small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>