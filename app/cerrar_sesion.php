<?php
// Requerimos la configuración para usar RUTA_BASE en la redirección
require_once __DIR__ . '/../config/config.php';

session_start();

// 1. Limpiamos todas las variables de sesión
$_SESSION = array();

// 2. Borramos la cookie de sesión en el navegador (Seguridad extra)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destruimos la sesión en el servidor
session_destroy();

// 4. Redirigimos al Login usando tu constante de ruta
// Añadimos un parámetro para que SweetAlert detecte que salió con éxito
header("Location: " . RUTA_BASE);
exit;

?>