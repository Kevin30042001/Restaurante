<?php
// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /proyecto/acceso_denegado.php");
    exit();
}

// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener el ID del usuario a eliminar
$id = $_GET['id'];

// Eliminar el usuario de la base de datos
$sql = "DELETE FROM usuarios WHERE id = ?";
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

header("Location: admin_usuarios.php");
exit();

sqlsrv_close($conn);
?>