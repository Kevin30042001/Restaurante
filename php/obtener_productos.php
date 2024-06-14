<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Consultar los productos desde la base de datos
$sql = "SELECT id, nombre, precio FROM productos";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los resultados como un array asociativo
$productos = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $productos[] = $row;
}

// Cerrar la conexión a la base de datos
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Devolver los productos como JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>