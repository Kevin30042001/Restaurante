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

// Obtener el ID de la reservación a editar
$id = $_GET['id'];

// Obtener los datos de la reservación de la base de datos
$sql = "SELECT * FROM reservaciones WHERE id = ?";
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Procesar el formulario de edición de reservación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $personas = $_POST["personas"];

    $sql = "UPDATE reservaciones SET nombre = ?, email = ?, telefono = ?, fecha = ?, hora = ?, personas = ? WHERE id = ?";
    $params = array($nombre, $email, $telefono, $fecha, $hora, $personas, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: admin_reservaciones.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Reservación</title>
</head>
<body>
    <h1>Editar Reservación</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $id; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="tel" name="telefono" value="<?php echo $row['telefono']; ?>" required><br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $row['fecha']->format('Y-m-d'); ?>" required><br>

        <label for="hora">Hora:</label>
        <input type="time" name="hora" value="<?php echo $row['hora']->format('H:i'); ?>" required><br>

        <label for="personas">Número de Personas:</label>
        <input type="number" name="personas" value="<?php echo $row['personas']; ?>" required><br>

        <input type="submit" value="Guardar Cambios">
    </form>

    <a href="admin_reservaciones.php">Volver a la Lista de Reservaciones</a>
</body>
</html>

<?php
sqlsrv_close($conn);
?>