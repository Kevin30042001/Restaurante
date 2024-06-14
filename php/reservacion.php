<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$hora = isset($_POST['hora']) ? $_POST['hora'] : '';
$personas = isset($_POST['personas']) ? $_POST['personas'] : '';

// Insertar los datos en la tabla de reservaciones
$sql = "INSERT INTO reservaciones (nombre, email, telefono, fecha, hora, personas) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($nombre, $email, $telefono, $fecha, $hora, $personas);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);

// Redirigir a una página de confirmación
header("Location: /proyecto/confirmacion.html");
exit();
?>