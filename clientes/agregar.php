<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require "../config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre    = trim($_POST["nombre"]);
    $apellido  = trim($_POST["apellido"]);
    $usuario   = trim($_POST["usuario"]);
    $correo    = trim($_POST["correo"]);
    $password  = trim($_POST["password"]);
    $rol       = $_POST["rol"];
    $estado    = $_POST["estado"];

    if (
        !empty($nombre) &&
        !empty($apellido) &&
        !empty($usuario) &&
        !empty($correo) &&
        !empty($password)
    ) {

        // Verificar si el usuario ya existe
        $consulta = $conexion->prepare("
            SELECT id
            FROM administradores
            WHERE usuario = ?
        ");

        $consulta->execute([$usuario]);

        if ($consulta->rowCount() > 0) {

            $mensaje = '
            <div class="alert alert-danger">
                El nombre de usuario ya existe.
            </div>';

        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insertar = $conexion->prepare("
                INSERT INTO administradores
                (
                    nombre,
                    apellido,
                    usuario,
                    correo,
                    password,
                    rol,
                    estado
                )
                VALUES
                (
                    ?,?,?,?,?,?,?
                )
            ");

            if ($insertar->execute([
                $nombre,
                $apellido,
                $usuario,
                $correo,
                $passwordHash,
                $rol,
                $estado
            ])) {

                $mensaje = '
                <div class="alert alert-success">
                    Administrador registrado correctamente.
                </div>';

            } else {

                $mensaje = '
                <div class="alert alert-danger">
                    Error al registrar el administrador.
                </div>';

            }

        }

    } else {

        $mensaje = '
        <div class="alert alert-warning">
            Complete todos los campos obligatorios.
        </div>';

    }

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Nuevo Administrador</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

<i class="bi bi-person-plus-fill"></i>

Nuevo Administrador

</h3>

</div>

<div class="card-body">

<?= $mensaje ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Nombre

</label>

<input
type="text"
name="nombre"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Apellido

</label>

<input
type="text"
name="apellido"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Usuario

</label>

<input
type="text"
name="usuario"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Correo

</label>

<input
type="email"
name="correo"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Contraseña

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Rol

</label>

<select
name="rol"
class="form-select">

<option value="Administrador">
Administrador
</option>

<option value="Empleado">
Empleado
</option>

</select>

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Estado

</label>

<select
name="estado"
class="form-select">

<option value="Activo">
Activo
</option>

<option value="Inactivo">
Inactivo
</option>

</select>

</div>

</div>

<hr>

<button
type="submit"
class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Guardar

</button>

<a
href="listar.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Volver

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>