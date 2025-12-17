<?php
//Este archivo se llamaba signIn.php y estaba en la carpeta /authentication/signIn/password
session_start();
require_once __DIR__ . "/../../php/conexion.php";  // Ruta corregida

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Este correo ya está registrado. <a href='../logIn/index.html'>Iniciar sesión</a>");
    } else {
        $_SESSION['email_registro'] = $email;
        header("Location: " . __DIR__ . "/password/createPassword.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
