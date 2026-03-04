<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

// 1. Recolección de datos
$nombre      = isset($_POST['nombre_platillo']) ? trim($_POST['nombre_platillo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$costo       = isset($_POST['costo']) ? trim($_POST['costo']) : '';
$categoria   = isset($_POST['id_categoria']) ? trim($_POST['id_categoria']) : '';
$estado      = isset($_POST['id_estado']) ? trim($_POST['id_estado']) : '';

// 2. Validación de campos de texto obligatorios
if (empty($nombre) || empty($costo) || empty($categoria) || empty($estado)) {
    header("Location: " . RUTA_BASE . "admin?error=vacio");
    exit;
}

// 3. Gestión y Validación de la Imagen
$ruta_para_db = '/../public/img_public/default.png'; 

if (isset($_FILES['imagen_platillo']) && $_FILES['imagen_platillo']['error'] !== UPLOAD_ERR_NO_FILE) {
    
    if ($_FILES['imagen_platillo']['error'] !== UPLOAD_ERR_OK) {
        header("Location: " . RUTA_BASE . "admin?error=archivo_danado");
        exit;
    }

    $fileTmpPath = $_FILES['imagen_platillo']['tmp_name'];
    $fileName    = $_FILES['imagen_platillo']['name'];
    $fileSize    = $_FILES['imagen_platillo']['size'];
    
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // --- CAMBIO AQUÍ: Array de extensiones permitidas ---
    $allowedExtensions = ['png', 'jpg', 'jpeg'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        header("Location: " . RUTA_BASE . "admin?error=formato_invalido");
        exit;
    }

    // VALIDACIÓN DE TAMAÑO: Máximo 2MB
    if ($fileSize > 2097152) {
        header("Location: " . RUTA_BASE . "admin?error=archivo_muy_grande");
        exit;
    }

    // Nombre único para evitar sobrescribir archivos
    $nuevoNombreArchivo = md5(time() . $fileName) . '.' . $fileExtension;
    $uploadFileDir = __DIR__ . '/../public/img_public/';
    $dest_path = $uploadFileDir . $nuevoNombreArchivo;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $ruta_para_db = '/../public/img_public/' . $nuevoNombreArchivo;
    } else {
        header("Location: " . RUTA_BASE . "admin?error=error_guardado");
        exit;
    }
}

try {
    // 4. Inserción en DB
    $resultado = registrarNewPlatillo(
        $pdo, 
        $categoria, 
        $nombre, 
        $descripcion, 
        $costo, 
        $estado, 
        $ruta_para_db
    );

    if ($resultado) {
        header("Location: " . RUTA_BASE . "admin?success=ok"); 
    } else {
        header("Location: " . RUTA_BASE . "admin?error=falla"); 
    }
} catch (PDOException $e) {
    header("Location: " . RUTA_BASE . "admin?error=db");
}
exit;