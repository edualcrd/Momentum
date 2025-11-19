<?php
session_start();
require_once "../../php/conexion.php"; // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Comprobar si ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si ya existe, mostrar mensaje o redirigir al login
        $stmt->close();
        $conn->close();
        die("Este correo ya está registrado. <a href='../logIn/index.html'>Iniciar sesión</a>");
    } else {
        // Guardar email en sesión y pasar a crear contraseña
        $_SESSION['email_registro'] = $email;
        $stmt->close();
        $conn->close();
        header("Location: password/createPassword.php");
        exit;
    }
}
?>
