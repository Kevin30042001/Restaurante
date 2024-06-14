<?php
// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener el correo electrónico del formulario
$email = isset($_POST['email']) ? $_POST['email'] : '';

// Verificar si el correo electrónico existe en la base de datos
$sql = "SELECT * FROM usuarios WHERE email = ?";
$params = array($email);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    // El correo electrónico existe
    $mensaje = "Se ha enviado un correo electrónico con las instrucciones para recuperar su contraseña.";
    $redireccion = "/proyecto/ForgotPassword.html";
} else {
    // El correo electrónico no existe
    $mensaje = "El correo electrónico ingresado no está registrado.";
    $redireccion = "/proyecto/ForgotPassword.html";
}

// Generar el mensaje en formato JavaScript
$mensajeJS = "alert('" . $mensaje . "'); window.location.href = '" . $redireccion . "';";

// Imprimir el mensaje en formato JavaScript
echo "<script>" . $mensajeJS . "</script>";

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);
?>