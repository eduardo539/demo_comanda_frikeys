<?php
session_start();

ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$id_categoria = $_GET['idCategoria'] ?? '';

if (!empty($id_categoria)) {
    // Llamamos a la función que devuelve el array asociativo
    $categoria = dataCategoriaSelect($pdo, $id_categoria);

    if ($categoria) {
        // Formateamos la salida para el JS: "ID|Nombre"
        echo $categoria['categoria_id'] . "|" . $categoria['categoria'];
    } else {
        // En caso de no encontrar nada, enviamos vacío o un error simple
        echo "0|No encontrado";
    }
} else {
    echo "0|ID no proporcionado";
}
exit;
