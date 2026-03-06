<?php
session_start();

ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$uuid = $_GET['uuid'] ?? '';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

if (empty($uuid)) {
    header("Location: " . RUTA_BASE . "admin?error=falla");
    exit;
}

if ($uuid) {
    $mesaData = obtenerMesaSelect($pdo, $uuid);
    if (!empty($mesaData)) {
        $mesa = $mesaData[0];
        // Devolvemos: Nombre|ID_Estado|UUID
        // Es vital devolver el ID del estado para que el select se mueva solo
        
        echo $mesa['nombre_mesa'] . "|" . $mesa['estado_gen_id'] . "|" . $mesa['uuid'] . "|" . $mesa['qr_img'];
    }
}
exit;
