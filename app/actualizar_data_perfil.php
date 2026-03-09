<?php
session_start();
ob_clean();


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "admin?error=sesion");
    exit;
}

$userID = $_POST['user_id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$edad = $_POST['edad'] ?? '';
$usuario = $_POST['usuario'] ?? '';

if (!empty($userID) && !empty($nombre) && !empty($apellidos) && !empty($telefono) && !empty($edad) && !empty($usuario)) {
    try {
        $resultado = actualizaUsuarioPerfil($pdo, $nombre, $apellidos, $telefono, $edad, $usuario, $userID);

        if ($resultado) {
            echo "success"; // Mensaje para el JavaScript
        } else {
            echo "error_falla";
        }
    } catch (PDOException $e) {
        echo "error_db";
    }
} else {
    echo "datos_incompletos";
}
exit;
