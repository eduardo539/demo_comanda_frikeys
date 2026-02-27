<?php

session_start();


// Recibimos el token desde la URL (viene del router)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';


$uuid = $_GET['token'] ?? ''; 

if (empty($uuid)) {
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}

// 2. CONSULTA DIRECTA A BASE DE DATOS
// Buscamos la mesa usando el UUID limpio para la prueba
$mesa_encontrada = obtenerNumeroMesa($pdo, $uuid);


if ($mesa_encontrada) {
    $_SESSION['cliente'] = [
        'mesa_id'      => $mesa_encontrada['mesa_id'],
        'nombre_mesa'  => $mesa_encontrada['nombre_mesa'],
        'uuid'         => $uuid,
        'token_sesion' => bin2hex(random_bytes(16)) 
    ];

    // Redireccionamos al menú (esta ruta sí pasa por el index/router)
    header("Location: " . RUTA_BASE . "menu");
    exit;
} else {
    header("Location: " . RUTA_BASE . "404");
    exit;
}


?>

/* 
function generar_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}




include('phpqrcode/qrlib.php'); // Incluyes la librería

$mesa_uuid = "550e8400-e29b-41d4-a716-446655440000";
$url_pedido = "https://tusitio.com/menu.php?mesa_id=" . $mesa_uuid;

// Nombre del archivo donde se guardará la imagen temporalmente
$archivo_qr = "qrs/mesa_1.png";

// Generar el código QR y guardarlo en el servidor
QRcode::png($url_pedido, $archivo_qr, QR_ECLEVEL_L, 10);

echo "<h3>Escanea para pedir:</h3>";
echo "<img src='".$archivo_qr."'>";

*/