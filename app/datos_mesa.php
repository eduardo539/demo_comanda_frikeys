<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$uuid = $_GET['uuid'] ?? '';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}

if (empty($uuid)) {
    header("Location: " . RUTA_BASE . "admin?error=falla");
    exit;
}

if ($uuid) {
    // 1. Llamas a tu función que ya tienes en consultas.php
    $mesaData = obtenerMesaSelect($pdo, $uuid);

    if (!empty($mesaData)) {
        // 2. Como es fetchAll, tomamos la primera fila
        $mesa = $mesaData[0];

        // 3. IMPORTANTÍSIMO: Imprimimos texto plano separado por PIPE |
        // Esto es lo que recibe tu .then(response => response.text())
        echo $mesa['nombre_mesa'] . "|" . $mesa['uuid'];
    }
}
exit;

