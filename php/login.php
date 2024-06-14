<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los datos del formulario
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Consultar la base de datos para verificar las credenciales y obtener el rol del usuario
$sql = "SELECT * FROM usuarios WHERE (email = ? OR nickname = ?) AND contrasena = ?";
$params = array($username, $username, $password);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    // Las credenciales son válidas, obtener el rol del usuario
    $usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $rol = $usuario['rol'];

    // Iniciar sesión y guardar el rol del usuario en una variable de sesión
    session_start();
    $_SESSION['rol'] = $rol;

    // Redirigir al usuario según su rol
    if ($rol === 'administrador') {
        header("Location: /proyecto/php/admin_dashboard.php");
        exit();
    } else {
        header("Location: /proyecto/Informacion.html");
        exit();
    }
} else {
    // Las credenciales son inválidas, mostrar mensaje de error
    echo "<script>alert('Usuario o contraseña incorrectos');</script>";
    echo "<script>window.location.href = '/proyecto/Index.html';</script>";
    exit();
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);
?>