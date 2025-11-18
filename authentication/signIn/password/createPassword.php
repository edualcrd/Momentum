<?php
session_start();

// Evitar acceso si no se ha registrado un email antes
if (!isset($_SESSION['email_registro'])) {
    header("Location: /authentication/signIn/signIn.php");
    exit;
}

$email = $_SESSION['email_registro']; // por si quieres mostrarlo en la UI
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear contraseña</title>
</head>
<body>
    <h1>Crear tu contraseña</h1>

    <p>Correo: <?php echo htmlspecialchars($email); ?></p>

    <form action="/authentication/signIn/password/savePassword.php" method="POST">
        <input type="password" name="password" placeholder="Crea una contraseña" required>
        <button type="submit">Registrar cuenta</button>
    </form>
</body>
</html>
