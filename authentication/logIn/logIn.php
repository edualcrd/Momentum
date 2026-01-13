<?php
session_start();
require_once __DIR__ . "/../../php/conexion.php"; // Ajusta según tu estructura

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Preparar consulta
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $hash = $row['password'];

        if (password_verify($password, $hash)) {
            // Login correcto
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_email'] = $email;
            header("Location: /Momentum/main/index.php"); // Página protegida
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El email no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Momentum/img/Momentum Icon.png">
    <title>Log In</title>
</head>
<body>

<div class="split-container">
    <div class="left-half">
        <img src="/Momentum/img/GIF Log In.gif" alt="Gif de un Stickman haciendo skate">
    </div>
    <div class="right-half">
        <div class="logoCompleto">
            <img src="/Momentum/img/Black Logo.png" alt="Logo de la marca">
        </div>

        <form class="login-form" action="logIn.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="input-field" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="input-field" required>

            <button type="submit" class="login-button">Entrar</button>

            <?php if(!empty($error)) { ?>
                <p style="color:red; margin-top:10px;"><?php echo $error; ?></p>
            <?php } ?>

            <div class="links-container">
                <a href="/Momentum/authentication/lostPassword/index.php">¿Has olvidado tu contraseña?</a>
                <a href="/Momentum/authentication/signIn/index.html">Crear cuenta</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
