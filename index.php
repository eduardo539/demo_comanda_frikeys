<?php
session_start();

// Esto detecta si estás en XAMPP (carpeta) o en un Hosting (raíz)
require_once __DIR__ . '/config/config.php';
// ----------------------------------------------------

// 1. Atrapamos lo que sea que mandó el .htaccess
$pagina_solicitada = $_GET['page'] ?? '';

// 2. EL INTERRUPTOR: Si el usuario escribió CUALQUIER COSA en la URL (que no sea la raíz)
if ($pagina_solicitada !== '') {

    // Llamamos a nuestro cerebro
    require_once __DIR__ . '/core/router.php';

    // El router nos devuelve el 404.php o la vista correcta
    $archivo_vista = despacharRuta($pagina_solicitada);

    // Mostramos la vista en pantalla
    include $archivo_vista;

    // ¡DETENEMOS EL CÓDIGO AQUÍ! Para que no se dibuje el login de abajo
    exit;
}


// =================================================================
// EL DIRECTOR DE TRÁFICO:
// =================================================================
if (isset($_SESSION['nombre_rol'])) {

    $rol = $_SESSION['nombre_rol'];

    if ($rol === 'ADMINISTRADOR') {
        header("Location: " . RUTA_BASE . "admin");
    } else if ($rol === 'COCINA') {
        header("Location: " . RUTA_BASE . "cocina");
    }

    exit;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="public/assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="bg-login">
        <div class="glass-card">

            <div class="text-center mb-4">
                <img src="public/assets/img/logo.jpg" alt="Logo Restaurante" class="brand-logo">
                <h3 class="fw-bold mt-2">Login FriKeys</h3>
                <p class="text-white-50 small">Panel de Administración y Cocina</p>
            </div>

            <form action="iniciar" method="POST">

                <div class="mb-4">
                    <label class="form-label small text-white-50 mb-1 ps-1">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="user" class="form-control border-start-0 ps-0" placeholder="Ej. admin" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small text-white-50 mb-1 ps-1">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="pass" class="form-control border-start-0 border-end-0 ps-0" id="passwordInput" placeholder="••••••••" required>
                        <span class="input-group-text border-start-0" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 mt-2">
                    Ingresar <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Detectamos si hay parámetros en la URL
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');

        // 2. Si existe el parámetro 'error', mostramos la alerta correspondiente
        if (error === 'credenciales_invalidas') {
            Swal.fire({
                title: "Error de acceso",
                text: "Usuario o contraseña incorrectos.",
                icon: "error",
                confirmButtonColor: "#d33"
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/js/login.js"></script>
</body>

</html>