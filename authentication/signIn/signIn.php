<?php
session_start();

// 1. Recibir el email del formulario
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    die("Correo no válido.");
}

// 2. Guardar email en sesión (temporal) 
// Si tienes base de datos, aquí harías el INSERT
$_SESSION['email_registro'] = $email;

// 3. Redirigir a la página para crear contraseña
header("Location: /authentication/signIn/password/createPassword.php");
exit;
?>
