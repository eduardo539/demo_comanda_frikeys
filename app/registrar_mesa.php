<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

// Incluimos la librería PHPQRCode (Asegúrate de que la ruta sea correcta)
require_once __DIR__ . '/../phpqrcode/qrlib.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

// Función para generar UUID v4 aleatorio
function generar_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// 1. Recolección de datos
$nombre_mesa = isset($_POST['nombre_mesa']) ? trim($_POST['nombre_mesa']) : '';
$id_estado   = isset($_POST['id_estado']) ? trim($_POST['id_estado']) : '';

// 2. Validación de campos obligatorios
if (empty($nombre_mesa) || empty($id_estado)) {
    header("Location: " . RUTA_BASE . "admin?error=vacio");
    exit;
}

// 3. Generación automática del UUID
$uuid_mesa = generar_uuid();

// 4. Generación del Código QR
$nombre_archivo_qr = 'qr_' . $uuid_mesa . '.png';
$ruta_fisica_qr = __DIR__ . '/../public/img_public/' . $nombre_archivo_qr;
$qrimg = '/../public/img_public/' . $nombre_archivo_qr; // Ruta para la DB

// Contenido del QR: RUTA_BASE + app/escanear?token= + uuid
$contenido_qr = "https://frikeys.infinityfreeapp.com/escanear?token=" . $uuid_mesa;

// Generar el archivo físico (Nivel de error L, tamaño 10)
QRcode::png($contenido_qr, $ruta_fisica_qr, QR_ECLEVEL_L, 10);



try {
    // 5. Inserción en la base de datos
    // Enviamos: PDO, Nombre de mesa, UUID, Ruta del QR generado e ID de estado
    $resultado = registrarNewMesa($pdo, $nombre_mesa, $uuid_mesa, $qrimg, $id_estado);

    if ($resultado) {
        header("Location: " . RUTA_BASE . "admin?success=ok"); 
    } else {
        header("Location: " . RUTA_BASE . "admin?error=falla"); 
    }
} catch (PDOException $e) {
    // Si falla la DB, intentamos borrar el archivo QR creado para no dejar basura
    if (file_exists($ruta_fisica_qr)) {
        unlink($ruta_fisica_qr);
    }
    
    $msg = ($e->getCode() == 23000) ? "duplicado" : "db";
    header("Location: " . RUTA_BASE . "admin?error=" . $msg);
}
exit;