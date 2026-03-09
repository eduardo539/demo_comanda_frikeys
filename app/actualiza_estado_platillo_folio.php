<?php
session_start();
ob_clean();


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "cocina?error=sesion");
    exit;
}

$folio = $_POST['folio'] ?? '';
$newEstado = $_POST['estado_id'] ?? '';

if (!empty($folio) && !empty($newEstado)) {
    try {
        $resultado = actualizarEstadoPedidoPorFolio($pdo, $folio, $newEstado);

        if ($resultado) {
            header("Location: " . RUTA_BASE . "cocina?success=ok"); 
        } else {
            header("Location: " . RUTA_BASE . "cocina?error=falla"); 
        }
    } catch (PDOException $e) {
        echo "error_db";
    }
} else {
    echo "datos_incompletos";
}
exit;
