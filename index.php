
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Alquiler Vehicular</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f4f6f9;
        }

        .hero{
            background:#0d6efd;
            color:white;
            padding:70px 20px;
            text-align:center;
        }

        .card{
            border:none;
            border-radius:15px;
            transition:.3s;
        }

        .card:hover{
            transform:translateY(-8px);
            box-shadow:0 10px 25px rgba(0,0,0,.2);
        }

        .card-body{
            padding:35px;
        }

        .icono{
            font-size:60px;
        }

    </style>

</head>



<body>



<div class="hero">

    <h1>Sistema de Alquiler Vehicular</h1>

    <p class="lead">
        Bienvenido al sistema de gestión.
    </p>
<button class="btn btn-danger"
        onclick="window.location='logout.php'">
    Cerrar sesión
</button>
</div>

<div class="container my-5">

    <div class="row justify-content-center g-4">

        <!-- Registrar Pago -->
        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <div class="icono">💳</div>

                    <h3 class="mt-3">Registrar Pago</h3>

                    <p class="text-muted">
                        Realice el registro de pagos correspondientes a los alquileres.
                    </p>

                    <a href="./pagos/registrar.php"
                       class="btn btn-warning w-100">
                        Registrar Pago
                    </a>

                </div>

            </div>

        </div>

        

        <!-- Iniciar Sesión -->
        <div class="col-md-4">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <div class="icono">🔐</div>

                    <h3 class="mt-3">Iniciar Sesión</h3>

                    <p class="text-muted">
                        Acceda al sistema con su usuario y contraseña.
                    </p>

                    <a href="login.php"
                       class="btn btn-primary w-100">
                        Iniciar Sesión
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<footer class="text-center py-4 text-secondary">

    © <?php echo date("Y"); ?> Sistema de Alquiler Vehicular

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```
