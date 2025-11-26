<?php
//Esta clase antes se llamaba conexion.php y estaba en la carpeta /php
$servername = "localhost";
$username = "root";
$password = "";
$database = "momentum_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
