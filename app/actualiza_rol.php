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

$rolID = $_POST['rol_id'] ?? '';
$newRol = $_POST['nombre_rol'] ?? '';

if (!empty($rolID) && !empty($newRol)) {
    try {
        $resultado = actualizaRol($pdo, $newRol, $rolID);

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
