<?php
session_start();
require_once __DIR__ . "/../Models/Connection.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $hash = $row['password'];

        if (password_verify($password, $hash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_email'] = $email;
            header("Location: /app/Views/main/profile.html");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El email no está registrado.";
    }

    $stmt->close();
}
?>
