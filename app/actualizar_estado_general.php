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

$estadoID = $_POST['id_gen'] ?? '';
$newEstado = $_POST['nombre_estado'] ?? '';

if (!empty($estadoID) && !empty($newEstado)) {
    try {
        $resultado = actualizarEstadoGen($pdo, $estadoID, $newEstado);

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
