<?php
session_start();

class AuthLostPassword {
    private $conn;
    private $error = "";
    private $success = "";

    public function __construct() {
        require_once __DIR__ . "/../Models/Connection.php";
        $this->conn = $conn;
    }

    public function handle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarSolicitud();
        }
        $this->mostrarVista();
    }

    private function procesarSolicitud() {
        $email = trim($_POST['email'] ?? '');

        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $token = bin2hex(random_bytes(16));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $stmt2 = $this->conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $user_id, $token, $expires);
            $stmt2->execute();
            $stmt2->close();

            $this->success = "Enlace enviado. <a href='?action=reset&token=$token'>Restablecer contraseña</a>";
        } else {
            $this->error = "El correo no está registrado.";
        }
        $stmt->close();
    }

    private function mostrarVista() {
        include __DIR__ . "/AuthLostPassword.php";
    }
}

$controller = new AuthLostPassword();
$controller->handle();
