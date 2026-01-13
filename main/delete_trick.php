<?php
session_start();
require_once __DIR__ . "/../php/conexion.php";

header('Content-Type: application/json');

// 1. Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// 2. Obtener ID del truco
$input = json_decode(file_get_contents('php://input'), true);
$trick_id = $input['id'] ?? null;
$user_id = $_SESSION['usuario_id'];

if (!$trick_id) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

// 3. Obtener la ruta del archivo para borrarlo
// IMPORTANTE: También verificamos "AND usuario_id = ?" para asegurarnos de que
// nadie borre los trucos de otro usuario manipulando el ID.
$stmt = $conn->prepare("SELECT media_url FROM trucos WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $trick_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Truco no encontrado o no tienes permiso']);
    exit;
}

$truco = $result->fetch_assoc();
$fileUrl = $truco['media_url'];

// 4. Borrar archivo del servidor
// Convertimos la URL relativa (ej: /Momentum/uploads/...) a ruta del sistema
$filePath = __DIR__ . "/../../" . str_replace("/Momentum/", "", $fileUrl);

if (file_exists($filePath)) {
    unlink($filePath); // Borra el archivo físico
}

// 5. Borrar de la base de datos
$stmt_del = $conn->prepare("DELETE FROM trucos WHERE id = ?");
$stmt_del->bind_param("i", $trick_id);

if ($stmt_del->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar de la BD']);
}

$stmt->close();
$stmt_del->close();
$conn->close();
?>