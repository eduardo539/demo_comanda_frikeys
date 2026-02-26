<?php
// app/auth_login.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?error=acceso_denegado");
    exit;
}

// 1. Llamamos a los archivos exactamente como definiste tu estructura
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/consultas.php';

$user_input = $_POST['user'] ?? '';
$pass_input = $_POST['pass'] ?? '';

// 2. Usamos la función de consultas.php
$usuario_db = obtenerUsuarioPorUsername($pdo, $user_input);

// 3. Lógica de validación
if ($usuario_db && $usuario_db['password'] === $pass_input) {
    
    $_SESSION['usuario_id']   = $usuario_db['id'];
    $_SESSION['usuario_nombre'] = $usuario_db['username'];
    $_SESSION['usuario_rol']  = $usuario_db['rol'];
    
    // Interacción entre ventanas (redirige usando el router)
    header("Location: /dashboard");
    exit;

} else {
    header("Location: /?error=credenciales_invalidas");
    exit;
}
?>