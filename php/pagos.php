<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los datos del pago enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

$nombre = isset($data['nombre']) ? $data['nombre'] : '';
$email = isset($data['email']) ? $data['email'] : '';
$productos = isset($data['productos']) ? $data['productos'] : array();

// Calcular el total a pagar
$total = 0;
foreach ($productos as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

// Insertar los datos del pago en la tabla de pagos
$sql = "INSERT INTO pagos (nombre, email, total) VALUES (?, ?, ?)";
$params = array($nombre, $email, $total);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$pago_id = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS id"))['id'];

// Insertar los detalles del pago en la tabla de detalles_pago
foreach ($productos as $producto) {
    $sql = "INSERT INTO detalles_pago (pago_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
    $params = array($pago_id, $producto['id'], $producto['cantidad'], $producto['precio']);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);

// Enviar una respuesta exitosa al cliente
http_response_code(200);
?>