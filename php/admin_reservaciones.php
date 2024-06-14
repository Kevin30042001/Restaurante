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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Reservaciones</title>
</head>
<body>
    <h1>Gestionar Reservaciones</h1>

    <h2>Lista de Reservaciones</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Personas</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM reservaciones";
        $stmt = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['telefono'] . "</td>";
            echo "<td>" . $row['fecha']->format('Y-m-d') . "</td>";
            echo "<td>" . $row['hora']->format('H:i:s') . "</td>";
            echo "<td>" . $row['personas'] . "</td>";
            echo "<td>";
            echo "<a href='editar_reservacion.php?id=" . $row['id'] . "'>Editar</a> | ";
            echo "<a href='eliminar_reservacion.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de eliminar esta reservación?\")'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <a href="admin_dashboard.php">Volver al Panel de Administración</a>
</body>
</html>

<?php
sqlsrv_close($conn);
?>