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

// Obtener el ID del elemento del menú a editar
$id = $_GET['id'];

// Obtener los datos del elemento del menú de la base de datos
$sql = "SELECT * FROM productos WHERE id = ?";
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Procesar el formulario de edición de elemento del menú
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $categoria = $_POST["categoria"];

    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria = ? WHERE id = ?";
    $params = array($nombre, $descripcion, $precio, $categoria, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: admin_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Elemento del Menú</title>
</head>
<body>
    <h1>Editar Elemento del Menú</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $id; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required><?php echo $row['descripcion']; ?></textarea><br>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $row['precio']; ?>" required><br>

        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" value="<?php echo $row['categoria']; ?>" required><br>

        <input type="submit" value="Guardar Cambios">
    </form>

    <a href="admin_menu.php">Volver a la Lista de Elementos del Menú</a>
</body>
</html>

<?php
sqlsrv_close($conn);
?>