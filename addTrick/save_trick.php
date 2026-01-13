<?php
session_start();
require_once __DIR__ . "/../php/conexion.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Momentum/authentication/logIn/logIn.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre_truco']);
    $fecha = $_POST['fecha'];

    // Manejo del archivo
    if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
        $file = $_FILES['media'];
        
        $fileName = $usuario_id . "_" . uniqid() . "_" . basename($file['name']);
        // Asegúrate de crear la carpeta 'uploads' en la raíz de tu proyecto o ajusta la ruta
        $targetDir = __DIR__ . "/../uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFilePath = $targetDir . $fileName;
        $dbUrl = "/Momentum/uploads/" . $fileName; // Ruta para guardar en BD

        $fileType = strpos($file['type'], 'video') !== false ? 'video' : 'gif'; // Simplificación (asumimos gif/img si no es video)

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // Insertar en BD
            $stmt = $conn->prepare("INSERT INTO trucos (usuario_id, nombre_truco, fecha, media_url, tipo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $usuario_id, $nombre, $fecha, $dbUrl, $fileType);

            if ($stmt->execute()) {
                header("Location: /Momentum/main/index.php"); // Éxito: volver al perfil
                exit;
            } else {
                echo "Error en base de datos: " . $stmt->error;
            }
        } else {
            echo "Error al subir archivo.";
        }
    } else {
        echo "Debes seleccionar un archivo.";
    }
}
