<?php
// views/cocina/home_cocina.php


// 1. Si intentan entrar directo al archivo, simplemente apagamos la página
if (!defined('RUTA_BASE')) {
    header("Location: ../../"); 
    exit;
}

// 2. Validación de rol
if (!isset($_SESSION['nombre_rol']) || $_SESSION['nombre_rol'] !== 'COCINA') {
    header("Location: " . RUTA_BASE);
    exit;
}

?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de cocina | FriKeys</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo RUTA_BASE; ?>public/assets/img/logo_ico.ico" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/dashboard_cocina.css">
</head>

<body>

    <div class="d-lg-none bg-dark p-3 d-flex align-items-center justify-content-between sticky-top" style="z-index: 1100;">

        <button id="sidebarCollapse" class="btn btn-primary border-0">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div>


    <div class="d-flex">

        <nav id="sidebar">

            <div class="sidebar-header">
                <h3 class="text-white fw-bold m-0">Fri<span style="color: var(--primary-color);">Keys</span></h3>
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
                    <li><a href="#" class="nav-link-ajax active" data-modulo="dashboard_cocina"><i class="bi bi-speedometer2"></i> Dashboard</a></li>

                    <span class="menu-label">Operaciones</span>
                    <!--<li><a href="#" class="nav-link-ajax" data-modulo="pedidos"><i class="bi bi-cart-check"></i> Pedidos Activos</a></li>-->
                    <li><a href="#" class="nav-link-ajax" data-modulo="pedidos_activos"><i class="bi bi-clock-history"></i> Pedidos Activos</a></li>

                    <span class="menu-label">Configuración</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="gestion_perfil"><i class="bi bi-person-gear"></i> Mi Perfil</a></li>


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
                <?php include './views/cocina/dashboard_cocina.php'; ?>
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


            if (params.get('error') === 'falla') {
                Swal.fire({
                    title: '¡Hay un error!',
                    text: 'Se produjo un error al intentar actualizar el estado del pedido',
                    icon: 'error',
                    confirmButtonColor: '#e53e3e', // Color acorde a tu gradiente
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    // Limpia la URL para que no repita la alerta si refrescan (F5)
                    window.history.replaceState({}, document.title, window.location.pathname);
                });
            }




            if (params.get('error') === 'db') {
                Swal.fire({
                    title: '¡Hay un error!',
                    text: 'Se produjo un error en la base de datos',
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
    <script src="<?php echo RUTA_BASE; ?>public/assets/js/home_cocina.js"></script>




</body>

</html>