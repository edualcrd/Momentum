<?php
session_start();
require_once __DIR__ . "/../php/conexion.php";

// 1. Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Momentum/authentication/logIn/logIn.php");
    exit;
}

$user_id = $_SESSION['usuario_id'];

// 2. Obtener datos del usuario
$stmt = $conn->prepare("SELECT username, grupo, biografia, foto_url FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Valores por defecto
$username = !empty($user['username']) ? $user['username'] : "Usuario";
$grupo = !empty($user['grupo']) ? $user['grupo'] : "Sin grupo";
$bio = !empty($user['biografia']) ? $user['biografia'] : "Sin biografía.";
// Si no hay foto en BD, usa la por defecto
$foto = !empty($user['foto_url']) ? $user['foto_url'] : "/Momentum/img/default-profile.jpg";

// 3. Obtener trucos del usuario
$trucos_sql = "SELECT * FROM trucos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt_trucos = $conn->prepare($trucos_sql);
$stmt_trucos->bind_param("i", $user_id);
$stmt_trucos->execute();
$result_trucos = $stmt_trucos->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/Momentum/main/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Momentum/img/Momentum Icon.png">
    <title>Perfil - Momentum</title>
</head>

<body>

    <div class="main-container">

        <header class="main-header">
            <div class="logo-area">
                <img src="/Momentum/img/Black Logo.png" alt="Logo de Momentum" class="header-logo">
            </div>
            <div class="user-area">
                <i class="fas fa-user-circle user-icon"></i>
                <a href="/Momentum/authentication/logIn/logIn.php?logout=1" class="logout-link">Cerrar Sesión</a>
            </div>
        </header>

        <section class="profile-section">
            <div class="profile-content">
                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Imagen de perfil" class="profile-pic" id="profilePicDisplay">

                <div class="profile-info">
                    <div id="viewMode">
                        <div class="user-details">
                            <h1 class="username">
                                <span id="usernameDisplay"><?php echo htmlspecialchars($username); ?></span>
                                <i class="fas fa-pencil-alt edit-icon" id="editIcon" title="Editar perfil"></i>
                            </h1>
                            <span class="profile-tag" id="groupDisplay"><?php echo htmlspecialchars($grupo); ?></span>
                        </div>
                        <p class="bio-text" id="bioDisplay"><?php echo htmlspecialchars($bio); ?></p>
                    </div>

                    <div id="editMode" style="display: none; width: 100%;">
                        <label style="font-weight:bold; font-size:14px;">Cambiar Foto:</label>
                        <input type="file" id="editPhoto" accept="image/*" style="margin-bottom: 10px; width: 100%;">

                        <input type="text" id="editUsername" value="<?php echo htmlspecialchars($username); ?>" placeholder="Usuario" style="margin-bottom: 10px; width: 100%; padding: 5px;">
                        <input type="text" id="editGroup" value="<?php echo htmlspecialchars($grupo); ?>" placeholder="Grupo" style="margin-bottom: 10px; width: 100%; padding: 5px;">
                        <textarea id="editBio" rows="3" style="width: 100%; padding: 5px;"><?php echo htmlspecialchars($bio); ?></textarea>

                        <button id="saveBtn" style="margin-top: 10px; padding: 5px 15px; cursor: pointer; background: #000; color: #fff; border: none; border-radius: 4px;">Guardar cambios</button>
                        <button id="cancelBtn" style="margin-top: 10px; padding: 5px 15px; cursor: pointer; background: #ccc; border: none; border-radius: 4px;">Cancelar</button>
                    </div>
                </div>
            </div>
        </section>

        <main class="content-section">
            <div class="tricks-gallery">
                <i class="fas fa-chevron-left carousel-arrow" id="leftArrow"></i>

                <div class="tricks-list" id="tricksList">
                    <?php if ($result_trucos->num_rows > 0): ?>
                        <?php while ($truco = $result_trucos->fetch_assoc()): ?>
                            <div class="trick-card" id="trick-<?php echo $truco['id']; ?>">

                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <h2 class="trick-name"><?php echo htmlspecialchars(strtoupper($truco['nombre_truco'])); ?></h2>

                                    <i class="fas fa-trash-alt delete-icon"
                                        data-id="<?php echo $truco['id']; ?>"
                                        style="color: #ff4d4d; cursor: pointer; margin-left: 10px;"
                                        title="Eliminar truco"></i>
                                </div>

                                <span class="trick-date"><?php echo date("d/m/Y", strtotime($truco['fecha'])); ?></span>

                                <div class="video-container">
                                    <?php if ($truco['tipo'] == 'video'): ?>
                                        <video class="trick-video" autoplay loop muted playsinline controls>
                                            <source src="<?php echo htmlspecialchars($truco['media_url']); ?>">
                                            Tu navegador no soporta este video.
                                        </video>
                                    <?php else: ?>
                                        <img src="<?php echo htmlspecialchars($truco['media_url']); ?>" class="trick-video gif-image">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="padding: 20px;">No tienes trucos aún. ¡Añade uno!</p>
                    <?php endif; ?>
                </div>

                <i class="fas fa-chevron-right carousel-arrow" id="rightArrow"></i>
            </div>

            <button class="add-trick-button" onclick="location.href='/Momentum/addTrick/index.html'">
                <i class="fas fa-plus"> </i>Añadir truco
            </button>
        </main>

    </div>
    <div id="toast-container"></div>

    <script>
        // Función global para mostrar notificaciones
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');

            // Crear elemento
            const toast = document.createElement('div');
            toast.classList.add('toast', type);

            // Icono según tipo
            const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';

            toast.innerHTML = `${icon} <span>${message}</span>`;
            container.appendChild(toast);

            // Animación de entrada
            setTimeout(() => {
                toast.classList.add('show');
            }, 100); // Pequeño retraso para permitir renderizado

            // Eliminar después de 3 segundos
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 500); // Esperar a que termine la transición CSS
            }, 3000);
        }

        // --- DETECTAR MENSAJES DE PHP (URL) ---
        // Esto sirve para cuando vienes de "Añadir Truco" o "Login"
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            const msg = urlParams.get('message') || 'Operación realizada con éxito';
            showToast(msg, 'success');
            // Limpiar URL para que no salga al recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.has('error')) {
            const msg = urlParams.get('message') || 'Ocurrió un error';
            showToast(msg, 'error');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script src="/Momentum/main/carousel.js"></script>
    <script src="/Momentum/main/profile.js"></script>
</body>

</html>