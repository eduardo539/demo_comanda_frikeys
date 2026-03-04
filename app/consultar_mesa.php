<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$uuid = $_GET['token'] ?? '';

if (empty($uuid)) {
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}

// 2. CONSULTA DIRECTA A BASE DE DATOS
$mesa_encontrada = obtenerNumeroMesa($pdo, $uuid);

// Verificamos que se haya encontrado algo y que el UUID coincida
if ($mesa_encontrada && $mesa_encontrada['uuid'] === $uuid) {

    // GUARDAR DATOS EN LA SESIÓN (Sin borrar el array $_SESSION)
    $_SESSION['mesa_id'] = $mesa_encontrada['id_mesa']; // Asegúrate que el nombre de columna sea igual al de tu BD
    $_SESSION['nombre_mesa'] = $mesa_encontrada['nombre_mesa'];
    $_SESSION['uuid'] = $mesa_encontrada['uuid'];
    
    // Si quieres un token de seguridad para la visita, guárdalo en una llave:
    $_SESSION['visita_token'] = bin2hex(random_bytes(16));

    // GUARDAMOS EL TIEMPO ACTUAL (en segundos)
    $_SESSION['creacion_sesion'] = time();

    // Redireccionamos al menú
    header("Location: " . RUTA_BASE . "menu");
    exit;
} else {
    // Si no existe la mesa o el UUID es incorrecto
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}



?>