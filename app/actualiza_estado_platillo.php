<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "cocina?error=sesion");
    exit;
}

$folio = $_POST['folio'] ?? '';
$estado_id = $_POST['estado_id'] ?? ''; // Esta es la variable que llega del formulario

if (!empty($folio) && !empty($estado_id)) {
    try {
        // CORRECCIÓN: Pasamos $estado_id (que es la que recibimos arriba)
        $resultado = actualizarEstadoPedidoPorFolio($pdo, $folio, $estado_id);

        if ($resultado) {
            header("Location: " . RUTA_BASE . "cocina?success=ok");
            exit;
        } else {
            header("Location: " . RUTA_BASE . "cocina?error=falla");
            exit;
        }
    } catch (PDOException $e) {
        $msg = ($e->getCode() == 23000) ? "duplicado" : "db";
        header("Location: " . RUTA_BASE . "cocina?error=" . $msg);
        exit;
    }
} else {
    // Si faltan datos, regresamos con error
    header("Location: " . RUTA_BASE . "cocina?error=datos_incompletos");
    exit;
}
