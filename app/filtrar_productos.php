<?php
session_start();
ob_clean();
header('Content-Type: application/json'); // Indicamos que devolvemos JSON

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$categoriaID = $_GET['idCategoria'] ?? '';

// Usamos fetchAll para traer todos los productos de esa categoría
$productos = dataProductosxCategoria($pdo, $categoriaID);

if ($productos) {
    echo json_encode($productos);
} else {
    echo json_encode([]);
}
exit;
