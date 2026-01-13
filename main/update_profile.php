<?php
session_start();
require_once __DIR__ . "/../php/conexion.php";

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user_id = $_SESSION['usuario_id'];

// Recogemos los datos usando $_POST (ya no JSON porque usamos FormData)
$username = trim($_POST['username'] ?? '');
$grupo = trim($_POST['grupo'] ?? '');
$biografia = trim($_POST['biografia'] ?? '');

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
    exit;
}

// Variables para la respuesta
$newPhotoUrl = null;
$photoUpdated = false;

// --- PROCESAR SUBIDA DE FOTO ---
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $file = $_FILES['foto'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (in_array($file['type'], $allowedTypes)) {
        // Carpeta destino (asegúrate de que existe)
        $targetDir = __DIR__ . "/../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        // Nombre único: perfil_ID_timestamp.ext
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = "profile_" . $user_id . "_" . time() . "." . $ext;
        $targetPath = $targetDir . $fileName;
        $dbUrl = "/Momentum/uploads/" . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $newPhotoUrl = $dbUrl;
            $photoUpdated = true;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover archivo']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Formato de imagen no válido']);
        exit;
    }
}

// --- ACTUALIZAR BASE DE DATOS ---

if ($photoUpdated) {
    // Si hay foto nueva, actualizamos todo
    $stmt = $conn->prepare("UPDATE usuarios SET username=?, grupo=?, biografia=?, foto_url=? WHERE id=?");
    $stmt->bind_param("ssssi", $username, $grupo, $biografia, $newPhotoUrl, $user_id);
} else {
    // Si NO hay foto nueva, actualizamos solo textos
    $stmt = $conn->prepare("UPDATE usuarios SET username=?, grupo=?, biografia=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $grupo, $biografia, $user_id);
}

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'newPhotoUrl' => $newPhotoUrl // Devolvemos la URL para que JS actualice la imagen
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>