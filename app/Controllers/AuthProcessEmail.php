<?php
session_start();
require_once __DIR__ . "/../Models/Connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        die("Este correo ya está registrado. <a href='/Momentum/app/Views/auth/login.php'>Iniciar sesión</a>");
    } else {
        $_SESSION['email_registro'] = $email;
        $stmt->close();
        $conn->close();
        header("Location: /app/Views/auth/createPassword.php");
        exit;
    }
}
?>
