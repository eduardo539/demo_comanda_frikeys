<?php
session_start();
ob_clean(); // Asegura que no haya espacios en blanco accidentales

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/bajas.php';


$idUsuario = $_POST['user_id'] ?? '';


if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "error=credenciales_invalidas");
    exit;
}


if (!empty($idUsuario)) {
    try {
        $resultado = eliminarUsuarioSelect($pdo, $idUsuario);

        if ($resultado) {
            echo "success";
        } else {
            // Quitamos el código 400 para que no salga en rojo en consola
            echo "error_falla";
        }
    } catch (PDOException $e) {
        // Captura de errores de base de datos (como llaves foráneas)
        if ($e->getCode() == '23000') {
            echo "db_relacion";
        } else {
            echo "error_db";
        }
    }
} else {
    echo "datos_incompletos";
}
exit;
