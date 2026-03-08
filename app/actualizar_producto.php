<?php
session_start();

// Limpiamos cualquier salida previa para no romper la respuesta AJAX
if (ob_get_length()) ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';

// 1. Verificación de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "admin?error=sesion");
    exit;
}

// 2. Captura de datos (Asegúrate de que los 'name' coincidan con tu HTML)
$productoID  = $_POST['producto_id'] ?? '';
$nombre      = trim($_POST['nombre_producto'] ?? '');
$descripcion = trim($_POST['descripcion_producto'] ?? '');
$costo       = $_POST['precio_producto'] ?? '';
$categoria   = $_POST['categoria_id'] ?? '';
$estado      = $_POST['estado_id'] ?? '';

// 3. Validación de campos obligatorios (Corregido: usamos las variables reales)
if (!empty($productoID) && !empty($nombre) && !empty($costo) && !empty($categoria) && !empty($estado)) {
    try {
        // Convertimos costo a float para asegurar precisión decimal
        $costoFloat = floatval($costo);

        // Llamada a la función (Asegúrate de que el orden de parámetros sea el mismo en actualizaciones.php)
        $resultado = actualizaProductos($pdo, $categoria, $nombre, $descripcion, $costoFloat, $estado, $productoID);

        if ($resultado) {
            echo "success"; 
        } else {
            echo "error_falla";
        }
    } catch (PDOException $e) {
        // Opcional: error_log($e->getMessage()); 
        echo "error_db";
    }
} else {
    echo "datos_incompletos";
}
exit;