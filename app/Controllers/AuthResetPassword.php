<?php

namespace App\Controllers;

require_once __DIR__ . "/../Models/Connection.php";

class AuthResetPassword
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function reset()
    {
        $error = "";
        $success = "";

        if (!isset($_GET['token'])) {
            return $this->render('error', ['message' => 'Token no proporcionado.']);
        }

        $token = $_GET['token'];
        
        if (!$this->validateToken($token, $user_id, $expires_at)) {
            return $this->render('error', ['message' => 'Token inválido o expirado.']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->updatePassword($_POST, $user_id, $token);
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $error = $result['message'];
            }
        }

        return $this->render('resetPassword', ['error' => $error, 'success' => $success]);
    }

    private function validateToken($token, &$user_id, &$expires_at)
    {
        $stmt = $this->conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            $expires_at = $row['expires_at'];
            return strtotime($expires_at) >= time();
        }
        return false;
    }

    private function updatePassword($data, $user_id, $token)
    {
        $password = $data['password'] ?? '';
        $confirm = $data['confirm_password'] ?? '';

        if ($password !== $confirm) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $this->conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
        $stmt->bind_param("si", $password_hash, $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt2 = $this->conn->prepare("DELETE FROM password_resets WHERE token=?");
        $stmt2->bind_param("s", $token);
        $stmt2->execute();
        $stmt2->close();

        return ['success' => true, 'message' => 'Contraseña actualizada correctamente.'];
    }

    private function render($view, $data = [])
    {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }
}
