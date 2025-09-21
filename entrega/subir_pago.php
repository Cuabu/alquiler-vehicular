<?php
// Configuración DB
$servername = "localhost";
$username = "root";   // cámbialo en producción
$password = "";       // cámbialo en producción
$dbname = "alquiler_motos_cali";

// Carpeta donde guardar los recibos
$carpeta_destino = "uploads/recibos/";

// Verificar si existe la carpeta
if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0777, true);
}

// Recibir datos
$placa = strtoupper(trim($_POST['placa']));
$archivo = $_FILES['recibo'];

// Validar archivo
if ($archivo['error'] === UPLOAD_ERR_OK) {
    $nombre_archivo = time() . "_" . basename($archivo['name']);
    $ruta_final = $carpeta_destino . $nombre_archivo;

    if (move_uploaded_file($archivo['tmp_name'], $ruta_final)) {
        // Guardar en DB
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("❌ Error DB: " . $conn->connect_error);
        }

        $sql = "INSERT INTO pagos_cuotas (placa_moto, archivo_recibo)
                VALUES ('$placa', '$nombre_archivo')";

        if ($conn->query($sql) === TRUE) {
            echo "✅ Recibo subido correctamente para la moto placa: " . $placa;
        } else {
            echo "❌ Error al guardar en DB: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "❌ Error al mover el archivo.";
    }
} else {
    echo "❌ Error al subir el archivo.";
}
?>
