<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "cocina?error=sesion");
    exit;
}

$estadoID = $_POST['estado'] ?? '';
$uuid = $_POST['uuid'] ?? ''; // Esta es la variable que llega del formulario

if (!empty($estadoID) && !empty($uuid)) {
    try {
        $resultado = actualizarEstadoMesaxuuid($pdo, $estadoID, $uuid);

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
