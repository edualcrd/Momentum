<?php
session_start();
require_once __DIR__ . "/../../php/conexion.php";

$error = "";
$success = "";

if (!isset($_GET['token'])) die("Token no proporcionado.");

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $expires_at = $row['expires_at'];

    if (strtotime($expires_at) < time()) die("El token ha expirado.");
} else {
    die("Token inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt2 = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
        $stmt2->bind_param("si", $password_hash, $user_id);
        $stmt2->execute();
        $stmt2->close();

        $stmt3 = $conn->prepare("DELETE FROM password_resets WHERE token=?");
        $stmt3->bind_param("s", $token);
        $stmt3->execute();
        $stmt3->close();

        $success = "Contraseña actualizada correctamente. <a href='/momentum/authentication/logIn/logIn.php'>Inicia sesión</a>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Restablecer contraseña</h1>

    <form action="" method="POST">
        <input type="password" name="password" placeholder="Nueva contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirma tu contraseña" required>
        <button type="submit">Actualizar contraseña</button>
    </form>

    <?php if(!empty($error)) { echo "<p class='error-msg'>$error</p>"; } ?>
    <?php if(!empty($success)) { echo "<p class='success-msg'>$success</p>"; } ?>
</div>
</body>
</html>
