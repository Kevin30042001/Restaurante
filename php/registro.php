<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los datos del formulario
$nombres = isset($_POST['nombres']) ? $_POST['nombres'] : '';
$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$genero = isset($_POST['genero']) ? $_POST['genero'] : '';
$nickname = isset($_POST['nickname']) ? $_POST['nickname'] : '';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';

// Validar los datos (puedes agregar más validaciones según tus requisitos)
if (empty($nombres) || empty($apellidos) || empty($email) || empty($genero) || empty($nickname) || empty($contrasena) || empty($fecha_nacimiento)) {
    die("Por favor, complete todos los campos.");
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombres, apellidos, email, genero, nickname, contrasena, fecha_nacimiento, rol)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'cliente')";
$params = array($nombres, $apellidos, $email, $genero, $nickname, $contrasena, $fecha_nacimiento);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);

// Mostrar mensaje de registro exitoso
echo "<script>alert('Registro exitoso');</script>";
echo "<script>window.location.href = '/proyecto/index.html';</script>";
exit();
?>