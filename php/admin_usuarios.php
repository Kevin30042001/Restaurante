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

// Procesar el formulario de creación de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $genero = $_POST["genero"];
    $nickname = $_POST["nickname"];
    $contrasena = $_POST["contrasena"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $rol = $_POST["rol"];

    $sql = "INSERT INTO usuarios (nombres, apellidos, email, genero, nickname, contrasena, fecha_nacimiento, rol) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($nombres, $apellidos, $email, $genero, $nickname, $contrasena, $fecha_nacimiento, $rol);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Usuarios</title>
</head>
<body>
    <h1>Gestionar Usuarios</h1>
    
    <h2>Crear Usuario</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required><br>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        
        <label for="genero">Género:</label>
        <select name="genero" required>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            <option value="Otro">Otro</option>
        </select><br>
        
        <label for="nickname">Nickname:</label>
        <input type="text" name="nickname" required><br>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" required><br>
        
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="cliente">Cliente</option>
            <option value="administrador">Administrador</option>
        </select><br>
        
        <input type="submit" value="Crear Usuario">
    </form>
    
    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Género</th>
            <th>Nickname</th>
            <th>Fecha de Nacimiento</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM usuarios";
        $stmt = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nombres'] . "</td>";
            echo "<td>" . $row['apellidos'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['genero'] . "</td>";
            echo "<td>" . $row['nickname'] . "</td>";
            echo "<td>" . $row['fecha_nacimiento']->format('Y-m-d') . "</td>";
            echo "<td>" . $row['rol'] . "</td>";
            echo "<td>";
            echo "<a href='editar_usuario.php?id=" . $row['id'] . "'>Editar</a> | ";
            echo "<a href='eliminar_usuario.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>Eliminar</a>";
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