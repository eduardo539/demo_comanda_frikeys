<?php

// Si no existe la constante, significa que intentaron entrar escribiendo la ruta
// de la carpeta directamente sin pasar por el index.php
if (!defined('RUTA_BASE')) {
    header("Location: ../../");
    exit;
}

// Si NO hay sesión iniciada O el rol NO es el de Administrador
if (!isset($_SESSION['nombre_rol']) || $_SESSION['nombre_rol'] !== 'ADMINISTRADOR') {
    header("Location: " . RUTA_BASE);
    exit;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | FriKeys</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo RUTA_BASE; ?>public/assets/img/logo_ico.ico" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_categoria.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_estados.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_productos.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_roles.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_usuarios.css">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/gestion_mesas.css">
</head>

<body>

    <div class="d-lg-none bg-dark p-3 d-flex align-items-center justify-content-between sticky-top" style="z-index: 1100;">

        <button id="sidebarCollapse" class="btn btn-primary border-0">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div>


    <div class="d-flex">

        <nav id="sidebar">

            <div class="sidebar-header" style="padding: 1.5rem; text-align: center; display: flex; justify-content: center; align-items: center;">
                <a href="#" style="text-decoration: none; display: block;">
                    <img src="<?php echo RUTA_BASE; ?>public/assets/img/logo_sin_contorno.png"
                        alt="Logo FriKeys"
                        style="max-width: 140px; height: auto; object-fit: contain; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'">
                </a>
            </div>

            <div class="user-profile-section">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['Nombre'], 0, 1)); ?>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['Nombre']); ?></span>
                <span class="user-role"><?php echo $_SESSION['nombre_rol']; ?></span>
            </div>


            <div class="sidebar-menu-container">
                <ul class="list-unstyled">
                    <li><a href="#" class="nav-link-ajax active" data-modulo="dashboard_admin"><i class="bi bi-speedometer2"></i> Dashboard</a></li>

                    <span class="menu-label">Gestión Core</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestionar_mesas"><i class="bi bi-shop"></i> Gestionar Mesas</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_estados"><i class="bi bi-flag"></i> Gestionar estados</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_productos"><i class="bi bi-egg-fried"></i> Menú / Platillos</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_categorias"><i class="bi bi-tags"></i> Categorías</a></li>

                    <span class="menu-label">Operaciones</span>
                    <!--<li><a href="#" class="nav-link-ajax" data-modulo="pedidos"><i class="bi bi-cart-check"></i> Pedidos Activos</a></li>-->
                    <li><a href="#" class="nav-link-ajax" data-modulo="historial_pedidos"><i class="bi bi-clock-history"></i> Historial Pedidos</a></li>

                    <span class="menu-label">Administración</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_usuarios"><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_roles"><i class="bi bi-shield-lock"></i> Roles de Sistema</a></li>


                    <span class="menu-label">Configuración</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_perfil"><i class="bi bi-person-gear"></i> Mi Perfil</a></li>
                    <li><a href="logout"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>


                </ul>
            </div>

            <div class="sidebar-footer border-top border-secondary border-opacity-25 p-3">
                <a href="logout" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2 rounded-3 fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
            </div>

        </nav>


        <div id="content">

            <div id="content-area">
                <?php include './views/modulos/dashboard_admin.php'; ?>
            </div>
        </div>



    </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Analizar la URL en busca de parámetros
            const params = new URLSearchParams(window.location.search);

            // 2. Si el registro fue exitoso
            if (params.get('success') === 'ok') {
                Swal.fire({
                    title: '¡Registro Exitoso!',
                    text: 'El nuevo registro ha sido añadido al sistema.',
                    icon: 'success',
                    confirmButtonColor: '#38b2ac', // Color acorde a tu gradiente
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    // Limpia la URL para que no repita la alerta si refrescan (F5)
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }

            // 3. Si hubo un error capturado en el header
            if (params.has('error')) {
                const errorType = params.get('error');
                let mensajeError = 'No se pudo completar el registro.';

                if (errorType === 'duplicado') mensajeError = 'Este registro ya existe en la base de datos.';
                if (errorType === 'vacio') mensajeError = 'El nombre del registro no puede estar vacío.';
                if (errorType === 'db') mensajeError = 'Error técnico en la base de datos.';

                Swal.fire({
                    title: 'Atención',
                    text: mensajeError,
                    icon: 'warning',
                    confirmButtonColor: '#e53e3e'
                }).then(() => {
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }




            if (params.get('error') === 'formato_invalido') {
                Swal.fire({
                    title: '¡Hay un error!',
                    text: 'El formato aceptado solo es PNG',
                    icon: 'error',
                    confirmButtonColor: '#e53e3e', // Color acorde a tu gradiente
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    // Limpia la URL para que no repita la alerta si refrescan (F5)
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }

            if (params.get('error') === 'archivo_muy_grande') {
                Swal.fire({
                    title: '¡Hay un error!',
                    text: 'La imagen debe pesar menos de 2MB',
                    icon: 'error',
                    confirmButtonColor: '#e53e3e', // Color acorde a tu gradiente
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    // Limpia la URL para que no repita la alerta si refrescan (F5)
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }

            if (params.get('error') === 'error_guardado') {
                Swal.fire({
                    title: '¡Hay un error!',
                    text: 'Se produjo un error al intentar guardar la imagen, intente de nuevo',
                    icon: 'error',
                    confirmButtonColor: '#e53e3e', // Color acorde a tu gradiente
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    // Limpia la URL para que no repita la alerta si refrescan (F5)
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }





        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo RUTA_BASE; ?>public/assets/js/admin_dashboard.js"></script>
    <script src="<?php echo RUTA_BASE; ?>public/assets/js/update_pass.js"></script>





</body>

</html>