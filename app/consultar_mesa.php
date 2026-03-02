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


?>