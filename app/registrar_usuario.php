<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

// 1. Recolección de datos con los nombres correctos del formulario
$nombre   = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$edad     = isset($_POST['edad']) ? trim($_POST['edad']) : '';
$usuario  = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$rol      = isset($_POST['id_rol']) ? trim($_POST['id_rol']) : '';
$estado   = isset($_POST['id_estado']) ? trim($_POST['id_estado']) : '';
$passw    = 'cambio123'; // Contraseña por defecto

// 2. Validación: que ningún campo importante esté vacío
if (empty($nombre) || empty($usuario) || empty($rol) || empty($estado)) {
    header("Location: " . RUTA_BASE . "admin?error=vacio");
    exit;
}

try {
    // 3. Llamada a la función con todos los parámetros
    $resultado = registrarNewUsuario($pdo, $nombre, $apellido, $telefono, $edad, $usuario, $passw, $rol, $estado);

    if ($resultado) {
        header("Location: " . RUTA_BASE . "admin?success=ok"); 
    } else {
        header("Location: " . RUTA_BASE . "admin?error=falla"); 
    }
} catch (PDOException $e) {
    // Manejo de errores (ej. usuario duplicado)
    $msg = ($e->getCode() == 23000) ? "duplicado" : "db";
    header("Location: " . RUTA_BASE . "admin?error=" . $msg);
}
exit;

?>