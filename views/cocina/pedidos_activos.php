<?php
include __DIR__ . '/../modulos/seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

// Ejecutamos la consulta corregida que devuelve el fetchAll
$pedidosActivos = obtenerPedidosCocina($pdo);
$totalPedidos = obtenerPedidoxFolio($pdo);
$totalxEntregar = obtenerPedidoxEntregar($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center justify-content-center justify-content-md-start">
                <i class="bi bi-fire text-dark me-3"></i>
                Pedidos en Cocina
            </h1>
            <p class="text-muted fs-5 mt-2">Monitoreo de comandas en preparación.</p>
        </div>
        <div class="col-md-4 text-center text-md-end">
            <div class="bg-white shadow-sm rounded-pill px-4 py-3 d-inline-block border-start border-dark border-4">
                <span class="text-muted small fw-bold text-uppercase">Por preparar: </span>
                <span class="text-dark fw-bold fs-5"><?php echo ($totalPedidos); ?></span>
            </div>
            <div class="bg-white shadow-sm rounded-pill px-4 py-3 d-inline-block border-start border-warning border-4">
                <span class="text-muted small fw-bold text-uppercase">Por Entregar: </span>
                <span class="text-warning fw-bold fs-5"><?php echo ($totalxEntregar); ?></span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="ps-4 py-4 text-uppercase small fw-bold text-muted">Folio</th>
                            <th class="py-4 text-uppercase small fw-bold text-muted">Mesa</th>
                            <th class="py-4 text-uppercase small fw-bold text-muted text-center">Cant. Productos</th>
                            <th class="py-4 text-uppercase small fw-bold text-muted text-center">Estado</th>
                            <th class="pe-4 py-4 text-uppercase small fw-bold text-muted text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidosActivos)): ?>
                            <?php foreach ($pedidosActivos as $pedido): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-dark text-white rounded-3 px-2 py-1 small fw-bold me-2">
                                                #<?php echo htmlspecialchars($pedido['folio']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-dark me-2"></i>
                                            <span class="fw-semibold text-dark"><?php echo htmlspecialchars($pedido['nombre_mesa']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-bold px-3 py-2">
                                            <?php echo number_format($pedido['cantidad'], 0); ?> unidades
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        // Definimos los estilos según el estado
                                        $estadoNombre = $pedido['estado_pedido'];
                                        $claseBadge = 'bg-secondary'; // Color por defecto
                                        $icono = 'bi-info-circle';    // Icono por defecto

                                        if ($estadoNombre === 'RECIBIDO') {
                                            $claseBadge = 'bg-success text-white'; // Verde
                                            $icono = 'bi-bell-fill';
                                        } elseif ($estadoNombre === 'PREPARANDO') {
                                            $claseBadge = 'bg-warning text-dark'; // Naranja/Amarillo
                                            $icono = 'bi-arrow-repeat spin-icon';
                                        }
                                        ?>

                                        <span class="badge <?php echo $claseBadge; ?> px-3 py-2 rounded-pill fw-bold shadow-sm">
                                            <i class="bi <?php echo $icono; ?> me-1"></i>
                                            <?php echo htmlspecialchars($estadoNombre); ?>
                                        </span>
                                    </td>

                                    <td class="pe-4 text-end">
                                        <button type="button"
                                            class="btn btn-primary rounded-0 py-2 px-4 fw-bold text-uppercase btn-detalle shadow-sm"
                                            style="background: linear-gradient(45deg, #dc644b, #dc644b); border: none; font-size: 0.8rem;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetallePedido"
                                            data-folio="<?php echo $pedido['folio']; ?>"
                                            data-estado="<?php echo $pedido['estado_pedido']; ?>">
                                            Ver Comanda
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        <p>No hay pedidos en preparación en este momento.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetallePedido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mt-2">Lista de Comanda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="bodyDetallePedido">
                <div class="text-center text-muted">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <p>Cargando platillos...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .spin-icon {
        display: inline-block;
        animation: rotation 2s infinite linear;
    }

    @keyframes rotation {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(359deg);
        }
    }

    .btn-detalle:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        transition: all 0.2s;
    }
</style>