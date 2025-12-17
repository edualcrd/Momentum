<?php
session_start();

require_once __DIR__ . "/../../../php/conexion.php";

if (!isset($_SESSION['email_registro'])) {
    header("Location: /Momentum/authentication/logIn/logIn.php");
    exit;
}

$email = $_SESSION['email_registro'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    die("Las contraseñas no coinciden. <a href='/Momentum/authentication/signUp/createPassword.php'>Volver</a>");
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (email, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password_hash);
$stmt->execute();

$stmt->close();
$conn->close();
unset($_SESSION['email_registro']);

header("Location: /Momentum/authentication/logIn/logIn.php");
exit;
