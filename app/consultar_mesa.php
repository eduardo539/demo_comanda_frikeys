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

// Si la mesa_encontrada es FALSE (no hay resultados), entrará al ELSE automáticamente
if ($mesa_encontrada) {
    // Si entramos aquí es porque la consulta SÍ regresó datos
    $_SESSION['mesa_id'] = $mesa_encontrada['mesa_id'] ?? $mesa_encontrada['id_mesa'];
    $_SESSION['nombre_mesa'] = $mesa_encontrada['nombre_mesa'];
    $_SESSION['uuid'] = $mesa_encontrada['uuid'];
    $_SESSION['creacion_sesion'] = time();

    header("Location: " . RUTA_BASE . "menu");
    exit;
} else {
    // Si la consulta regresó vacía (porque id != 1 o uuid incorrecto)
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}
