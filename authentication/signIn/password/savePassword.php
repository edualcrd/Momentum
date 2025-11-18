<?php
session_start();

// Evitar acceso ilegal
if (!isset($_SESSION['email_registro'])) {
    header("Location: /authentication/signIn/signIn.php");
    exit;
}

$email = $_SESSION['email_registro'];
$password = $_POST['password'];

// Encriptar contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// =====================================
// Aquí harías el INSERT en la base de datos
//
// Ejemplo:
//
// $pdo = new PDO("mysql:host=localhost;dbname=tu_base", "root", "");
// $stmt = $pdo->prepare("INSERT INTO usuarios (email, password) VALUES (?,?)");
// $stmt->execute([$email, $hash]);
//
// =====================================

// Limpiar sesión
unset($_SESSION['email_registro']);

// Redirigir al login
header("Location: /authentication/logIn/index.html");
exit;
?>
