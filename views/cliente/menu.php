<?php

// Definimos el límite: 15 minutos * 60 segundos = 900 segundos
$minutos_limite = 20;
$segundos_limite = $minutos_limite * 60;


if (!defined('RUTA_BASE')) {
    header("Location: ../../"); 
    exit;
}

// Si NO hay sesión iniciada O el rol NO es el de Administrador
if (!isset($_SESSION['uuid'])) {
    header("Location: " . RUTA_BASE . "error_scan");
    exit;
}


if (isset($_SESSION['creacion_sesion'])) {
    $segundos_transcurridos = time() - $_SESSION['creacion_sesion'];

    if ($segundos_transcurridos > $segundos_limite) {
        // ¡TIEMPO AGOTADO! 
        // Limpiamos la sesión para que tenga que escanear de nuevo
        session_unset();
        session_destroy();
        
        // Redirigimos a la página de error con un mensaje específico
        header("Location: " . RUTA_BASE . "error_scan");
        exit;
    }
}


$nombreMesa = $_SESSION['nombre_mesa'] ?? 'Mesa Desconocida';
$idMesa = $_SESSION['mesa_id'] ?? 0;


?><script>alert("Nombre de la mesa: <?php echo htmlspecialchars($nombreMesa); ?>");</script><?php


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú | Frikeys Café Restaurante</title>

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/menu_cliente.css">
</head>

<body>

    <header class="menu-header">
        <div class="container position-relative">
            <div class="d-flex align-items-center justify-content-between">
                <button class="btn-menu-categorias d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                    <i class="bi bi-list"></i>
                </button>

                <div class="text-center w-100">
                    <h1 class="brand-font">Frikeys</h1>
                    <p class="brand-subtitle mb-0">Café Restaurante</p>
                </div>

                <div class="d-md-none" style="width: 45px;"></div>
            </div>

            <nav class="d-none d-md-flex justify-content-center mt-4">
                <div class="desktop-nav">
                    <button class="cat-btn active" data-category="todos">✨ Todos</button>
                    <button class="cat-btn" data-category="cafe">☕ Cafés</button>
                    <button class="cat-btn" data-category="comida">🍔 Comida</button>
                    <button class="cat-btn" data-category="postres">🍰 Postres</button>
                </div>
            </nav>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCategories">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">Categorías</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush">
                <button class="list-group-item list-group-item-action cat-btn active" data-category="todos" data-bs-dismiss="offcanvas">✨ Todos</button>
                <button class="list-group-item list-group-item-action cat-btn" data-category="cafe" data-bs-dismiss="offcanvas">☕ Cafés</button>
                <button class="list-group-item list-group-item-action cat-btn" data-category="comida" data-bs-dismiss="offcanvas">🍔 Comida</button>
                <button class="list-group-item list-group-item-action cat-btn" data-category="postres" data-bs-dismiss="offcanvas">🍰 Postres</button>
            </div>
        </div>
    </div>

    <main class="container py-5">
        <div class="row g-4" id="contenedor-productos">
            <div class="col-12 col-md-6 col-lg-4 producto-item" data-cat="cafe">
                <div class="card frikeys-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=500" alt="Capuchino">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Capuchino Premium</h5>
                        <p class="text-muted small">Doble shot de espresso con leche cremosa y un toque de canela.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">$3.50</span>
                            <button class="btn-frikeys btn-agregar" data-nombre="Capuchino" data-precio="3.50">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4 producto-item" data-cat="comida">
                <div class="card frikeys-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500" alt="Burger">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Frikeys Burger</h5>
                        <p class="text-muted small">Carne artesanal, queso cheddar, tocino y nuestra salsa especial.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">$8.50</span>
                            <button class="btn-frikeys btn-agregar" data-nombre="Frikeys Burger" data-precio="8.50">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4 producto-item" data-cat="postres">
                <div class="card frikeys-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=500" alt="Crepa">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Crepa de Nutella</h5>
                        <p class="text-muted small">Rellena de chocolate, fresas frescas y azúcar glass.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price">$5.25</span>
                            <button class="btn-frikeys btn-agregar" data-nombre="Crepa Nutella" data-precio="5.25">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
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
</body>

</html>