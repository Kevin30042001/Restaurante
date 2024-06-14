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

// Obtener el ID del usuario a editar
$id = $_GET['id'];

// Obtener los datos del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Procesar el formulario de edición de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $genero = $_POST["genero"];
    $nickname = $_POST["nickname"];
    $contrasena = $_POST["contrasena"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $rol = $_POST["rol"];

    $sql = "UPDATE usuarios SET nombres = ?, apellidos = ?, email = ?, genero = ?, nickname = ?, contrasena = ?, fecha_nacimiento = ?, rol = ? WHERE id = ?";
    $params = array($nombres, $apellidos, $email, $genero, $nickname, $contrasena, $fecha_nacimiento, $rol, $id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: admin_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $id; ?>">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" value="<?php echo $row['nombres']; ?>" required><br>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $row['apellidos']; ?>" required><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        
        <label for="genero">Género:</label>
        <select name="genero" required>
            <option value="Masculino" <?php if ($row['genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
            <option value="Femenino" <?php if ($row['genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
            <option value="Otro" <?php if ($row['genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
        </select><br>
        
        <label for="nickname">Nickname:</label>
        <input type="text" name="nickname" value="<?php echo $row['nickname']; ?>" required><br>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" value="<?php echo $row['fecha_nacimiento']->format('Y-m-d'); ?>" required><br>
        
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="cliente" <?php if ($row['rol'] == 'cliente') echo 'selected'; ?>>Cliente</option>
            <option value="administrador" <?php if ($row['rol'] == 'administrador') echo 'selected'; ?>>Administrador</option>
        </select><br>
        
        <input type="submit" value="Guardar Cambios">
    </form>
    
    <a href="admin_usuarios.php">Volver a la Lista de Usuarios</a>
</body>
</html>

<?php
sqlsrv_close($conn);
?>