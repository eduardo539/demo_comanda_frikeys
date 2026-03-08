<?php
session_start();

ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$usuarioID = $_GET['idUser'] ?? '';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

if (empty($usuarioID)) {
    header("Location: " . RUTA_BASE . "admin?error=falla");
    exit;
}

if ($usuarioID) {
    $resultado = dataUsuarioSelect($pdo, $usuarioID);

    if($resultado)
        {
            echo $resultado['user_id'] . "|" .
            $resultado['Nombre'] . "|" .
            $resultado['Apellidos'] . "|" .
            $resultado['telefono'] . "|" .
            $resultado['usuario'] . "|" .
            $resultado['rol_id'] . "|" .
            $resultado['nombre_rol'] . "|" .
            $resultado['estado_gen_id'] . "|" .
            $resultado['estado'];
        } else {
        echo "0|No encontrado";
    }

    
}
exit;
