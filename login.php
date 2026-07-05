<?php
session_start();

if(isset($_SESSION['id'])){

    header("Location: dashboard.php");
    exit;

}

require "./config/conexion.php";

$mensaje="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $usuario=trim($_POST["usuario"]);
    $password=trim($_POST["password"]);

    if(!empty($usuario) && !empty($password)){

        $sql=$conexion->prepare("
            SELECT *
            FROM administradores
            WHERE usuario=?
            AND estado='Activo'
        ");

        $sql->execute([$usuario]);

        if($sql->rowCount()==1){

            $datos=$sql->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password,$datos["password"])){

                $actualizar=$conexion->prepare("
                    UPDATE administradores
                    SET ultimo_acceso=NOW()
                    WHERE id=?
                ");

                $actualizar->execute([$datos["id"]]);

                session_regenerate_id(true);

                $_SESSION["id"]=$datos["id"];
                $_SESSION["nombre"]=$datos["nombre"];
                $_SESSION["apellido"]=$datos["apellido"];
                $_SESSION["usuario"]=$datos["usuario"];
                $_SESSION["correo"]=$datos["correo"];
                $_SESSION["rol"]=$datos["rol"];

                header("Location: dashboard.php");
                exit;

            }else{

                $mensaje='
                <div class="alert alert-danger">
                    Contraseña incorrecta.
                </div>';

            }

        }else{

            $mensaje='
            <div class="alert alert-danger">
                Usuario no encontrado.
            </div>';

        }

    }else{

        $mensaje='
        <div class="alert alert-warning">
            Complete todos los campos.
        </div>';

    }

}
?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Iniciar Sesión</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>

body{

background:#eef2f7;

}

.card{

border:none;

border-radius:15px;

box-shadow:0 10px 25px rgba(0,0,0,.15);

}

.logo{

font-size:70px;

color:#0d6efd;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-md-4">

<div class="card">

<div class="card-body p-4">

<div class="text-center mb-4">

<i class="bi bi-car-front-fill logo"></i>

<h3 class="mt-3">

Sistema de Alquiler

</h3>

<p class="text-muted">

Iniciar Sesión

</p>

</div>

<?= $mensaje ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Usuario

</label>

<input
type="text"
name="usuario"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Contraseña

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-primary w-100">

<i class="bi bi-box-arrow-in-right"></i>

Ingresar

</button>

</form>

<hr>

<div class="text-center">

Sistema de Gestión de Vehículos

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>