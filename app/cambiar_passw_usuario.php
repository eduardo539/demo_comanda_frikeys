<?php
session_start();

// Limpiamos cualquier salida previa para no romper la respuesta AJAX
if (ob_get_length()) ob_clean();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/actualizaciones.php';
require_once __DIR__ . '/../core/consultas.php';


// 1. Verificación de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: " . RUTA_BASE . "admin?error=sesion");
    exit;
}



// 2. Captura de datos (IMPORTANTE: Coincidir con el name del HTML)
$idUser = $_POST['user_id'] ?? ''; // Cambiado de id_user a user_id
$passActual = $_POST['pass_actual'] ?? '';
$nuevoPass = $_POST['pass_nueva'] ?? '';


// 3. Validación de campos obligatorios
if (!empty($idUser) && !empty($passActual) && !empty($nuevoPass)) {

    // Obtenemos los datos del usuario
    $usuario_db = obtenerUsuarioPorID($pdo, $idUser);

    // Generamos el hash de la contraseña actual ingresada para comparar
    // Si en tu DB el hash es SHA256, lo replicamos aquí:
    $pass_actual_hash = hash('sha256', $passActual);

    if ($usuario_db && $usuario_db['passw'] === $pass_actual_hash) {

        try {
            // Hasheamos la nueva contraseña antes de enviarla a la base de datos
            $nuevoHash = hash('sha256', $nuevoPass);
            
            $resultado = actualizaPassUsuario($pdo, $nuevoHash, $idUser);

            if ($resultado) {
                echo "success";
            } else {
                echo "error_falla";
            }
        } catch (PDOException $e) {
            echo "error_db";
        }
    } else {
        echo "pass_incorrecto";
    }
} else {
    echo "datos_incompletos";
}
exit;