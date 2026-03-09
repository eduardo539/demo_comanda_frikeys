<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

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

// 3. Obtener datos de la mesa
$nombreMesa = $_SESSION['nombre_mesa'] ?? 'Mesa Desconocida';
$idMesa = $_SESSION['mesa_id'] ?? 0;

// Consultas
$categorias = obtenerCategorias($pdo);
$productos = obtenerDataPlatillosCliente($pdo);


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú | <?php echo htmlspecialchars($nombreMesa); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo RUTA_BASE; ?>public/assets/css/menu_cliente.css">
</head>

<body>

    <header class="menu-header sticky-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <button class="btn btn-light rounded-circle shadow-sm d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                    <i class="bi bi-grid-fill text-primary"></i>
                </button>
                <div class="text-center flex-grow-1">
                    <h1 class="brand-font">Frikeys</h1>
                    <p class="brand-subtitle mb-0">Café Restaurante</p>
                    <div class="mt-2">
                        <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm">
                            <i class="bi bi-geo-alt-fill text-info me-1"></i> <?php echo htmlspecialchars($nombreMesa); ?>
                        </span>
                    </div>
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

    <main class="container py-5">
        <div class="row g-4" id="contenedor-productos">
            <?php foreach ($productos as $prod): ?>
                <div class="col-12 col-md-6 col-lg-4 producto-item category-<?php echo $prod['categoria_id']; ?>">
                    <div class="card frikeys-card h-100 border-0 shadow-sm">
                        <div class="img-wrapper position-relative">
                            <?php $img = !empty($prod['imagen']) ? ltrim($prod['imagen'], '/. ') : 'public/img_public/default.png'; ?>
                            <img src="<?php echo RUTA_BASE . $img; ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" class="img-fluid rounded-top">
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge price-tag rounded-pill shadow-sm">
                                    $<?php echo number_format($prod['costo'], 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                            <p class="text-muted small mb-4"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-agregar"
                                    data-id="<?php echo $prod['producto_id']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                    data-precio="<?php echo $prod['costo']; ?>">
                                    <i class="bi bi-bag-plus-fill me-2"></i>Añadir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="cart-floating" class="cart-container d-none fixed-bottom m-4 shadow-lg p-3 bg-primary rounded-pill text-white justify-content-between align-items-center" style="z-index: 1050; max-width: 400px; margin-left: auto !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <i class="bi bi-bag-heart-fill fs-3"></i>
                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
            </div>
            <span id="cart-total-amount" class="fw-bold">$0.00</span>
        </div>
        <button class="btn btn-light rounded-pill px-4 fw-bold" onclick="abrirModalPedido()">Ver Pedido</button>
    </div>

    <div class="modal fade" id="modalPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">Tu Pedido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody id="lista-pedido-cuerpo"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded-3">
                        <span class="fw-bold fs-5">Total:</span>
                        <span class="fw-bold fs-5 text-primary" id="modal-total-amount">$0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary px-4" onclick="enviarPedidoFinal()">Confirmar Pedido</button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        let carrito = JSON.parse(localStorage.getItem('carrito_frikeys')) || [];
        const RUTA_BASE = '<?php echo RUTA_BASE; ?>';
        const ID_MESA = Number(<?php echo $_SESSION['mesa_id'] ?? 0; ?>);

        document.addEventListener("DOMContentLoaded", () => {
            initFiltros();
            actualizarInterfaz();
            vincularBotonesAgregar();
        });

        function vincularBotonesAgregar() {
            document.querySelectorAll(".btn-agregar").forEach(btn => {
                btn.onclick = function() {
                    const item = {
                        id: this.dataset.id,
                        nombre: this.dataset.nombre,
                        precio: parseFloat(this.dataset.precio),
                        cantidad: 1
                    };
                    agregarAlCarrito(item);
                };
            });
        }

        function agregarAlCarrito(nuevo) {
            const existe = carrito.find(p => p.id === nuevo.id);
            if (existe) {
                existe.cantidad++;
            } else {
                carrito.push(nuevo);
            }
            guardarYActualizar();
        }

        function guardarYActualizar() {
            localStorage.setItem('carrito_frikeys', JSON.stringify(carrito));
            actualizarInterfaz();
        }

        function actualizarInterfaz() {
            const flotante = document.getElementById("cart-floating");
            const count = document.getElementById("cart-count");
            const total = document.getElementById("cart-total-amount");

            if (carrito.length > 0) {
                flotante.classList.remove("d-none");
                flotante.classList.add("d-flex");
                count.innerText = carrito.reduce((acc, p) => acc + p.cantidad, 0);
                total.innerText = `$${carrito.reduce((acc, p) => acc + (p.precio * p.cantidad), 0).toFixed(2)}`;
            } else {
                flotante.classList.add("d-none");
            }
        }

        function abrirModalPedido() {
            const cuerpo = document.getElementById("lista-pedido-cuerpo");
            cuerpo.innerHTML = "";
            carrito.forEach((p, i) => {
                cuerpo.innerHTML += `
                    <tr>
                        <td><strong>${p.nombre}</strong><br><small>$${p.precio}</small></td>
                        <td class="text-center">${p.cantidad}</td>
                        <td class="text-end">$${(p.precio * p.cantidad).toFixed(2)}</td>
                        <td><button class="btn btn-sm text-danger" onclick="eliminar(${i})"><i class="bi bi-trash"></i></button></td>
                    </tr>`;
            });
            document.getElementById("modal-total-amount").innerText = `$${carrito.reduce((acc, p) => acc + (p.precio * p.cantidad), 0).toFixed(2)}`;
            new bootstrap.Modal(document.getElementById('modalPedido')).show();
        }

        function eliminar(i) {
            carrito.splice(i, 1);
            guardarYActualizar();
            if (carrito.length > 0) abrirModalPedido();
            else location.reload();
        }

        function initFiltros() {
            document.querySelectorAll(".cat-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const cat = this.getAttribute("data-category");
                    document.querySelectorAll(".producto-item").forEach(item => {
                        item.style.display = (cat === "todos" || item.classList.contains(`category-${cat}`)) ? "block" : "none";
                    });
                    document.querySelectorAll(".cat-btn").forEach(b => b.classList.remove("active"));
                    this.classList.add("active");
                });
            });
        }




        function enviarPedidoFinal() {
            if (carrito.length === 0) return;

            if (ID_MESA === 0) {
                Swal.fire("Error", "No se detectó el número de mesa. Recarga la página.", "error");
                return;
            }

            // Mostrar estado de carga
            Swal.fire({
                title: 'Enviando pedido...',
                text: 'Por favor espera un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('mesa_id', ID_MESA);

            carrito.forEach(p => {
                formData.append('productos_ids[]', p.id);
                formData.append('cantidades[]', p.cantidad);
                formData.append('subtotales[]', (p.precio * p.cantidad).toFixed(2));
            });

            fetch('registrarVenta', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    if (data.includes("EXITO") || data.includes("OK")) {
                        // ALERTA DE ÉXITO PERSONALIZADA
                        Swal.fire({
                            title: '¡Pedido Confirmado!',
                            html: `
                        <div class="text-center">
                            <i class="bi bi-clock-history fs-1 text-primary"></i>
                            <p class="mt-3">Tu pedido se ha realizado correctamente.</p>
                            <div class="alert alert-info">
                                <strong>Tiempo estimado:</strong><br>
                                10 a 15 minutos (máximo 20 min).
                            </div>
                            <p class="small text-muted">¡Gracias por tu preferencia!</p>
                        </div>
                    `,
                            icon: 'success',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#0d6efd', // Color primary de Bootstrap
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                localStorage.removeItem('carrito_frikeys');
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire("Error", "Mensaje del servidor: " + data, "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
                });
        }
    </script>
</body>

</html>