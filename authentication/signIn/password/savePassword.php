<?php
session_start();

if (!isset($_SESSION['email_registro'])) {
    header("Location: index.html");
    exit;
}

$email = $_SESSION['email_registro'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    die("Las contraseñas no coinciden. <a href='createPassword.php'>Volver</a>");
}

// Encriptar la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Aquí guardarías en la base de datos
// require 'conexion.php';
// $stmt = $pdo->prepare("INSERT INTO usuarios (email, password) VALUES (?,?)");
// $stmt->execute([$email, $hash]);

// Limpiar sesión
unset($_SESSION['email_registro']);

// Redirigir al login
header("Location: login.html");
exit;
?>
