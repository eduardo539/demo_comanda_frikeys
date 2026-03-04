<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php'; // Asumiendo que aquí están tus SELECT

// 1. Configuración de tiempo de sesión (15 min)
$minutos_limite = 15;
$segundos_limite = $minutos_limite * 60;

if (!isset($_SESSION['uuid'])) {
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}

// 2. Control de expiración
if (isset($_SESSION['creacion_sesion'])) {
    $segundos_transcurridos = time() - $_SESSION['creacion_sesion'];
    if ($segundos_transcurridos > $segundos_limite) {
        session_unset();
        session_destroy();
        header("Location: " . RUTA_BASE . "error_scan?razon=tiempo_agotado");
        exit;
    }
}

// 3. Obtener datos de la mesa y base de datos
$nombreMesa = $_SESSION['nombre_mesa'] ?? 'Mesa Desconocida';
$idMesa = $_SESSION['mesa_id'] ?? 0;

// Consultas dinámicas
$categorias = obtenerCategorias($pdo); // Función que debes tener
$productos = obtenerDataPlatillos($pdo);   // Función que debes tener
?>






<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú | <?php echo $nombreMesa; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/menu_cliente.css">
</head>

<body>

    <header class="menu-header sticky-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <button class="btn-menu-categorias shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                    <i class="bi bi-grid-fill"></i>
                </button>

                <div class="text-center flex-grow-1">
                    <h1 class="brand-font">Frikeys</h1>
                    <p class="brand-subtitle mb-0">Café Restaurante</p>
                    <div class="mt-2">
                        <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.75rem;">
                            <i class="bi bi-geo-alt-fill text-info me-1"></i> <?php echo htmlspecialchars($nombreMesa); ?>
                        </span>
                    </div>
                </div>

                <div style="width: 45px;" class="d-flex justify-content-end">
                    <i class="bi bi-clock-history text-white opacity-75"></i>
                </div>
            </div>

            <nav class="d-none d-md-flex justify-content-center mt-4">
                <div class="desktop-nav">
                    <button class="cat-btn active" data-category="todos">✨ Todos</button>
                    <?php foreach ($categorias as $cat): ?>
                        <button class="cat-btn" data-category="<?php echo $cat['categoria_id']; ?>">
                            <?php echo htmlspecialchars($cat['categoria']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </nav>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCategories">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title fw-bold">Nuestro Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush">
                <button class="list-group-item list-group-item-action cat-btn active" data-category="todos" data-bs-dismiss="offcanvas">✨ Todos</button>
                <?php foreach ($categorias as $cat): ?>
                    <button class="list-group-item list-group-item-action cat-btn"
                        data-category="<?php echo $cat['categoria_id']; ?>" data-bs-dismiss="offcanvas">
                        <?php echo htmlspecialchars($cat['categoria']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <main class="container py-5">
        <div class="row g-4" id="contenedor-productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $prod): ?>
                    <div class="col-12 col-md-6 col-lg-4 producto-item" data-cat="<?php echo $prod['producto_id']; ?>">
                        <div class="card frikeys-card h-100 border-0">
                            <div class="img-wrapper position-relative">
                                <?php
                                $img = !empty($prod['imagen']) ? ltrim($prod['imagen'], '/. ') : 'public/img_public/default.png';
                                ?>
                                <img src="<?php echo RUTA_BASE . $img; ?>" alt="<?php echo $prod['nombre']; ?>" class="loading-lazy">

                                <div class="position-absolute bottom-0 start-0 m-3">
                                    <span class="badge bg-dark bg-opacity-50 backdrop-blur rounded-pill text-white border border-light border-opacity-25">
                                        $<?php echo number_format($prod['costo'], 2); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                                <p class="text-muted small mb-4" style="line-height: 1.5; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?php echo htmlspecialchars($prod['descripcion']); ?>
                                </p>

                                <div class="d-grid">
                                    <button class="btn btn-frikeys shadow-sm btn-agregar w-100"
                                        data-id="<?php echo $prod['producto_id']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                        data-precio="<?php echo $prod['costo']; ?>">
                                        <i class="bi bi-bag-plus-fill me-2"></i>Añadir al pedido
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>



    <div id="cart-floating" class="cart-container d-none">
        <div class="cart-info">
            <i class="bi bi-bag-heart-fill fs-4"></i>
            <span id="cart-count">0 items</span>
        </div>
        <div class="cart-total">
            <span id="cart-total-amount">$0.00</span>
        </div>
        <button class="btn-ver-pedido">Ver Pedido</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/js/js_menu_cliente.js"></script>