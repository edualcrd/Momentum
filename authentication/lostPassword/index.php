<?php
session_start();
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . "/../../php/conexion.php";

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt2 = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt2->bind_param("iss", $user_id, $token, $expires);
        $stmt2->execute();
        $stmt2->close();

        // Para pruebas, mostramos el enlace directamente
        $success = "Enlace de recuperación: <a href='resetPassword.php?token=$token'>Restablecer contraseña</a>";
    } else {
        $error = "El correo no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Recuperar contraseña</h1>
    <p>Introduce tu correo electrónico</p>

    <form action="" method="POST">
        <input type="email" name="email" placeholder="tuemail@mail.com" required>
        <button type="submit">Enviar enlace</button>
    </form>

    <?php if(!empty($error)) { echo "<p class='error-msg'>$error</p>"; } ?>
    <?php if(!empty($success)) { echo "<p class='success-msg'>$success</p>"; } ?>
</div>
</body>
</html>
