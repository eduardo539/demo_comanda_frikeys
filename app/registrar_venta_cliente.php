<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/registros.php';

// Ejemplo para Ciudad de México (ajusta según tu ciudad)
date_default_timezone_set('America/Mexico_City');

// Capturar los datos del FormData
$idMesa     = $_POST['mesa_id'] ?? null;
$ids        = $_POST['productos_ids'] ?? []; // DEBEN COINCIDIR CON EL JS
$cantidades = $_POST['cantidades'] ?? [];
$subtotales = $_POST['subtotales'] ?? [];

if (empty($ids)) {
    exit("ERROR: El arreglo de productos está vacío.");
}

if (!$idMesa) {
    exit("ERROR: No se recibió mesa_id. Datos recibidos: " . count($_POST));
}

try {
    // PREPARACIÓN DE DATOS
    $folioComun  = "FOL-" . date('Ymd') . "-" . rand(1000, 9999);
    $fechaComun  = date('Y-m-d H:i:s');
    $estadoId    = 1;
    
    $arregloFinalParaInsertar = [];

    foreach ($ids as $index => $id) {
        $arregloFinalParaInsertar[] = [
            'folio'       => $folioComun,
            'fecha'       => $fechaComun,
            'producto_id' => $id,
            'cantidad'    => $cantidades[$index],
            'total'       => $subtotales[$index],
            'mesa_id'     => $idMesa,
            'estado_id'   => $estadoId
        ];
    }

    // Enviamos el "paquetote" ya listo a la función
    $resultado = registrarVentaCompleta($pdo, $arregloFinalParaInsertar);

    echo $resultado['success'] 
        ? "EXITO|{$resultado['folio']}" 
        : "ERROR|{$resultado['error']}";

} catch (Exception $e) {
    echo "ERROR_CRITICO|" . $e->getMessage();
}