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
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
$categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';

// Insertar los datos en la tabla de productos
$sql = "INSERT INTO productos (nombre, categoria, precio, descripcion) VALUES (?, ?, ?, ?)";
$params = array($nombre, $categoria, $precio, $descripcion);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);

// Redirigir a una página de confirmación
header("Location: /proyecto/confirmacion_producto.html");
exit();
?>