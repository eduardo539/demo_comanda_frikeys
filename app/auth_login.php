<?php
// app/auth_login.php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // CORRECCIÓN 1: Evitar que salga a XAMPP si intentan entrar directo
    header("Location: " . RUTA_BASE . "?error=acceso_denegado");
    exit;
}


$user_input = $_POST['user'] ?? '';
$pass_input = $_POST['pass'] ?? '';

$usuario_db = obtenerUsuarioPorUsername($pdo, $user_input);

// Lógica de validación
if ($usuario_db && $usuario_db['passw'] === $pass_input) {
    
    // Guardamos los datos en la "memoria"
    $_SESSION['user_id']    = $usuario_db['user_id'];
    $_SESSION['Nombre']     = $usuario_db['Nombre'];
    $_SESSION['usuario']    = $usuario_db['usuario'];
    $_SESSION['nombre_rol'] = $usuario_db['nombre_rol'];
    
    $rol = $_SESSION['nombre_rol'];

    if ($rol === 'ADMINISTRADOR') {
        // CORRECCIÓN 2: Mandarlo a la ruta de admin de TU proyecto
        header("Location: " . RUTA_BASE . "admin"); 
    } 
    else if ($rol === 'COCINA') {
        // CORRECCIÓN 3: Mandarlo a cocina de TU proyecto
        header("Location: " . RUTA_BASE . "cocina");
    } 
    else {
        // CORRECCIÓN 4: Error de rol dentro de TU proyecto
        header("Location: " . RUTA_BASE . "?error=rol_desconocido");
    }
    
    exit;

} else {
    // CORRECCIÓN 5: ¡Este es el que te fallaba! Regresa a TU login con error
    header("Location: " . RUTA_BASE . "?error=credenciales_invalidas");
    exit;
}
?>