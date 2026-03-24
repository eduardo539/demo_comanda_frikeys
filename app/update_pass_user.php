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
$pass_reset = 'cambio123';


if (!empty($pass_reset) && !empty($userID)) {
    try {

        $resultado = resetPassUser($pdo, $pass_reset, $userID);

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