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

    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/admin_dashboard.css">
</head>

<body>

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
                    <li><a href="#" class="nav-link-ajax active" data-modulo="dashboard_admin"><i class="bi bi-speedometer2"></i> Dashboard</a></li>

                    <span class="menu-label">Gestión Core</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="mesas"><i class="bi bi-shop"></i> Gestionar Mesas</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="estados"><i class="bi bi-flag"></i> Gestionar estados</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="platillos"><i class="bi bi-egg-fried"></i> Menú / Platillos</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="categorias"><i class="bi bi-tags"></i> Categorías</a></li>

                    <span class="menu-label">Operaciones</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="pedidos"><i class="bi bi-cart-check"></i> Pedidos Activos</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="historial"><i class="bi bi-clock-history"></i> Historial Pedidos</a></li>

                    <span class="menu-label">Administración</span>
                    <li><a href="#" class="nav-link-ajax" data-modulo="usuarios"><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="#" class="nav-link-ajax" data-modulo="roles"><i class="bi bi-shield-lock"></i> Roles de Sistema</a></li>

                    <li class="mt-4">
                        <a href="logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>

        </nav>


        <div id="content">

            <div id="content-area">
                <?php include './views/modulos/dashboard_admin.php'; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo RUTA_BASE; ?>public/assets/js/admin_dashboard.js"></script>
</body>

</html>