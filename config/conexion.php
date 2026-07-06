```php
<?php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$baseDatos = "alquiler";

$conexion = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer la codificación de caracteres
$conexion->set_charset("utf8mb4");

?>
```
