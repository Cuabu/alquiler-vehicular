<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Panel Administrativo</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>

body{
    background:#f5f6fa;
}

.navbar{
    box-shadow:0 2px 10px rgba(0,0,0,.15);
}

.card{
    border:none;
    border-radius:15px;
}

.card:hover{

transform:translateY(-4px);

transition:.3s;

}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container-fluid">

<a class="navbar-brand" href="dashboard.php">

<i class="bi bi-car-front-fill"></i>

Sistema de Alquiler

</a>

<button class="navbar-toggler" type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav me-auto">

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
role="button"
data-bs-toggle="dropdown">

<i class="bi bi-people-fill"></i>

Clientes

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="clientes/agregar.php">Agregar Cliente</a></li>

<li><a class="dropdown-item" href="clientes/listar.php">Ver Clientes</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-car-front"></i>

Vehículos

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="vehiculos/agregar.php">Agregar Vehículo</a></li>

<li><a class="dropdown-item" href="vehiculos/listar.php">Ver Vehículos</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-calendar-check"></i>

Alquileres

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="alquileres/nuevo.php">Nuevo Alquiler</a></li>

<li><a class="dropdown-item" href="alquileres/listar.php">Historial</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-cash-stack"></i>

Pagos

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="pagos/registrar.php">Registrar Pago</a></li>

<li><a class="dropdown-item" href="pagos/historial.php">Historial</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-tools"></i>

Mantenimiento

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="mantenimiento/agregar.php">Nuevo Mantenimiento</a></li>

<li><a class="dropdown-item" href="mantenimiento/listar.php">Historial</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-shield-check"></i>

Seguros

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="seguros/agregar.php">Registrar Seguro</a></li>

<li><a class="dropdown-item" href="seguros/listar.php">Ver Seguros</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-exclamation-triangle"></i>

Multas

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="multas/agregar.php">Registrar Multa</a></li>

<li><a class="dropdown-item" href="multas/listar.php">Ver Multas</a></li>

</ul>

</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"
href="#"
data-bs-toggle="dropdown">

<i class="bi bi-person-gear"></i>

Administradores

</a>

<ul class="dropdown-menu">

<li><a class="dropdown-item" href="administradores/agregar.php">Nuevo Administrador</a></li>

<li><a class="dropdown-item" href="administradores/listar.php">Ver Administradores</a></li>

</ul>

</li>

</ul>

<a href="logout.php"
class="btn btn-danger">

<i class="bi bi-box-arrow-right"></i>

Salir

</a>

</div>

</div>

</nav>

<div class="container mt-5">

<div class="row">

<div class="col-md-12">

<div class="card shadow">

<div class="card-body">

<h2>

Bienvenido

<strong>

<?php echo $_SESSION['nombre']; ?>

</strong>

</h2>

<hr>

<h4>Panel Principal</h4>

<p>

Aquí podrás administrar clientes, vehículos, alquileres, pagos, mantenimientos, seguros, multas y administradores.

</p>

<p>

Este contenido es solo una plantilla para que luego agregues gráficos, estadísticas, reportes y cualquier otra información que necesites.

</p>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>