<?php
session_start();

ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$productoID = $_GET['idProducto'] ?? '';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

if (empty($productoID)) {
    header("Location: " . RUTA_BASE . "admin?error=falla");
    exit;
}

if ($productoID) {
    $resultado = dataProductoSelect($pdo, $productoID);

    if($resultado)
        {
            echo $resultado['categoria_id'] . "|" .
            $resultado['categoria'] . "|" .
            $resultado['producto_id'] . "|" .
            $resultado['nombre'] . "|" .
            $resultado['descripcion'] . "|" .
            $resultado['costo'] . "|" .
            $resultado['imagen'] . "|" .
            $resultado['estado_gen_id'] . "|" .
            $resultado['estado'];
        } else {
        echo "0|No encontrado";
    }

    
}
exit;
