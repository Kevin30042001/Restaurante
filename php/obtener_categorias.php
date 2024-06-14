<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Consultar las categorías desde la base de datos
$sql = "SELECT id, nombre FROM categorias";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los resultados como un array asociativo
$categorias = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $categorias[] = $row;
}

// Cerrar la conexión a la base de datos
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Devolver las categorías como JSON
header('Content-Type: application/json');
echo json_encode($categorias);
?>