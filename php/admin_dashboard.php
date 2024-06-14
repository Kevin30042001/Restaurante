<?php
// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /proyecto/php/acceso_denegado.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Bienvenido al Panel de Administración</h1>
    <ul>
        <li><a href="admin_usuarios.php">Gestionar Usuarios</a></li>
        <li><a href="admin_reservaciones.php">Gestionar Reservaciones</a></li>
        <li><a href="admin_menu.php">Gestionar Menú</a></li>
        <li><a href="admin_reportes.php">Generar Reportes</a></li>
    </ul>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</body>
</html>