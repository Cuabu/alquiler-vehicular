<?php
// Configuración DB
$servername = "localhost";
$username = "root";      // cámbialo en producción
$password = "";          // cámbialo en producción
$dbname = "alquiler_motos_cali";

// Recibir ID
$transaction_id = $_POST['transaction_id'];

// Conectar DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// 🔑 Token Nequi (colocar el real desde Nequi Developers)
$access_token = "TU_TOKEN_NEQUI";

// Llamada a la API de Nequi (ejemplo)
$url = "https://api.sandbox.nequi.com/payments/v1/check";

$data = [
    "transactionId" => $transaction_id
];

$options = [
    "http" => [
        "header"  => "Content-type: application/json\r\n" .
                     "Authorization: Bearer " . $access_token . "\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    die("❌ Error al consultar API Nequi");
}

$response = json_decode($result, true);

// Revisar estado
$estado = "PENDIENTE";
if (isset($response['status'])) {
    if ($response['status'] == "APPROVED") {
        $estado = "APROBADO";
    } elseif ($response['status'] == "REJECTED") {
        $estado = "RECHAZADO";
    }
}

// Guardar en BD
$sql = "INSERT INTO pagos_nequi (transaction_id, cliente_nombre, cliente_celular, monto, estado)
        VALUES ('$transaction_id','Cliente Desconocido','0000000000','0','$estado')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Pago registrado con estado: " . $estado;
} else {
    echo "❌ Error en DB: " . $conn->error;
}

$conn->close();
?>
