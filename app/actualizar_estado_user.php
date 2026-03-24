<?php
session_start();

// Limpiamos cualquier salida previa para no romper la respuesta AJAX
if (ob_get_length()) ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

// 1. Verificación de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "admin?error=sesion");
    exit;
}

$userID = $_POST['user_id'] ?? '';
$estadoID = $_POST['estado_id'] ?? '';


if (!empty($estadoID) && !empty($userID)) {
    try {

        $resultado = actualizaEstadoUser($pdo, $estadoID, $userID);

        if ($resultado) {
            echo "success"; 
        } else {
            echo "error_falla";
        }
    } catch (PDOException $e) {
        // Opcional: error_log($e->getMessage()); 
        echo "error_db";
    }
} else {
    echo "datos_incompletos";
}
exit;