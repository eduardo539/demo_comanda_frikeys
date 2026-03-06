<?php
session_start();

ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$idEstado = $_GET['idEstado'] ?? '';

if (!empty($idEstado)) {
    // Llamamos a la función que devuelve el array asociativo
    $estado = obtenerEstadoGenSelect($pdo, $idEstado);

    if ($estado) {
        // Formateamos la salida para el JS: "ID|Nombre"
        echo $estado['estado_gen_id'] . "|" . $estado['estado'];
    } else {
        // En caso de no encontrar nada, enviamos vacío o un error simple
        echo "0|No encontrado";
    }
} else {
    echo "0|ID no proporcionado";
}
exit;
