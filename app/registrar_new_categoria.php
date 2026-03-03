<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

$categoria = isset($_POST['nombre_categoria']) ? trim($_POST['nombre_categoria']) : '';

if (empty($categoria)) {
    header("Location: " . RUTA_BASE . "admin?error=vacio");
    exit;
}

try {
    $resultado = registrarNewCategoria($pdo, $categoria);

    if ($resultado) {
        // Redireccionamos con un parámetro de éxito
        header("Location: " . RUTA_BASE . "admin?success=ok"); 
    } else {
        header("Location: " . RUTA_BASE . "admin?error=falla"); 
    }
} catch (PDOException $e) {
    $msg = ($e->getCode() == 23000) ? "duplicado" : "db";
    header("Location: " . RUTA_BASE . "admin?error=" . $msg);
}
exit;

?>