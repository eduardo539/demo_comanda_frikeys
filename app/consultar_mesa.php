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
// Verificamos que se haya encontrado algo
if ($mesa_encontrada && $mesa_encontrada['uuid'] === $uuid) {

    // Cambia 'id_mesa' por 'mesa_id' si ese es el nombre en tu tabla
    // Usamos el operador null coalescing ?? para evitar errores
    $_SESSION['mesa_id'] = $mesa_encontrada['mesa_id'] ?? $mesa_encontrada['id_mesa'] ?? $mesa_encontrada['id'];

    $_SESSION['nombre_mesa'] = $mesa_encontrada['nombre_mesa'] ?? $mesa_encontrada['nombre'];
    $_SESSION['uuid'] = $mesa_encontrada['uuid'];

    $_SESSION['creacion_sesion'] = time();

    header("Location: " . RUTA_BASE . "menu");
    exit;
} else {
    // Si no existe la mesa o el UUID es incorrecto
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}
