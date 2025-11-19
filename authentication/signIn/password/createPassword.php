<?php
    session_start();
    if (!isset($_SESSION['email_registro'])) {
        header("Location: ../../logIn/logIn.php");
        exit;
    }
    $email = $_SESSION['email_registro'];
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Crear contraseña</title>
        <link rel="stylesheet" href="createPasswordStyles.css">
    </head>
    <body>
        <div class="container">
            <img class="logo" src="/momentum/img/Logo Blanco.png" alt="Logo de la aplicación">
            <h1>Crear tu contraseña</h1>
            <p>Correo: <?php echo htmlspecialchars($email); ?></p>

            <form action="savePassword.php" method="POST">
                <input type="password" name="password" placeholder="Crea una contraseña" required>
                <input type="password" name="confirm_password" placeholder="Confirma tu contraseña" required>
                <button type="submit">Registrar cuenta</button>
            </form>
        </div>
    </body>
    </html>