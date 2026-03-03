<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

$nombre = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';

if (empty($nombre)) {
    header("Location: " . RUTA_BASE . "admin?error=vacio");
    exit;
}

try {
    $resultado = registrarNewRol($pdo, $nombre);

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