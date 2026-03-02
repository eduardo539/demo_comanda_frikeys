<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// En lugar de cargar TODO el config.php que puede traer el router o redirecciones...
// Solo definimos la constante si no existe, o cargamos un archivo de puras constantes.
if (!defined('RUTA_BASE')) {
    // Si tu config.php es pesado, podrías definir RUTA_BASE manualmente aquí
    // o asegurarte de que config.php SOLO tenga constantes.
    require_once __DIR__ . '/../../config/config.php'; 
}

// VALIDACIÓN CLAVE
if (!isset($_SESSION['user_id'])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('HTTP/1.1 401 Unauthorized');
        die("SESION_EXPIRADA"); // Enviamos un texto simple, no HTML
    }
    header("Location: " . RUTA_BASE);
    exit;
}

// Si llegamos aquí, el módulo se cargará normal.
?>