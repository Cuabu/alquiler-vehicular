<?php
// index.php
date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BitWave Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background:#f4f6f9;
        }

        .sidebar{

            width:260px;
            min-height:100vh;
            background:#212529;
        }

        .sidebar a{

            color:#dee2e6;
            text-decoration:none;
            display:block;
            padding:12px 20px;
            transition:.2s;

        }

        .sidebar a:hover{

            background:#0d6efd;
            color:white;

        }

        .card{

            border:none;
            border-radius:15px;

        }

    </style>

</head>

<body>

<div class="d-flex">

    <!-- MENU -->

    <div class="sidebar">

        <h3 class="text-center text-white py-4">

            <i class="bi bi-cpu"></i>

            BitWave

        </h3>

        <a href="#"><i class="bi bi-house"></i> Inicio</a>

        <a href="#"><i class="bi bi-folder"></i> Archivos</a>

        <a href="#"><i class="bi bi-pc-display"></i> Equipos</a>

        <a href="#"><i class="bi bi-hdd-network"></i> Agentes</a>

        <a href="#"><i class="bi bi-wifi"></i> WebSocket</a>

        <a href="#"><i class="bi bi-gear"></i> Configuración</a>

    </div>

    <!-- CONTENIDO -->

    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h2>Dashboard</h2>

                <small class="text-muted">
                    <?php echo date("d/m/Y H:i:s"); ?>
                </small>

            </div>

        </div>

        <hr>

        <div class="row">

            <div class="col-md-3 mb-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h5>Equipos</h5>

                        <h2>0</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h5>Agentes</h5>

                        <h2>0</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h5>Conectados</h5>

                        <h2>0</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h5>Versión</h5>

                        <h2>1.0</h2>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header">

                Actividad reciente

            </div>

            <div class="card-body">

                <p class="text-muted">

                    Bienvenido al panel de administración de BitWave.

                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>