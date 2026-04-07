<?php
include __DIR__ . '/../modulos/seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

// Aquí deberías llamar a funciones que cuenten estados específicos
// Ejemplo hipotético:
// $pendientes = contarPedidosPorEstado($pdo, 'PENDIENTE');
// $enPreparacion = contarPedidosPorEstado($pdo, 'PREPARANDO');
$dataTotalRecibido = obtenerTotalRecibidos($pdo);
$dataTotalPreparando = obtenerTotalPreparando($pdo);
$totalPlatillos = obtenerTotalActivos($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-4">
        <div class="col-12 text-center text-lg-start">
            <h2 class="fw-bold m-0 text-dark">RESUMEN DE COCINA</h2>
            <p class="text-secondary">Estado actual de la operativa</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white border-start border-danger border-5">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-danger-subtle text-danger me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Por Iniciar</h6>
                        <h2 class="fw-bold mb-0"><?php echo $dataTotalRecibido ?? 0; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white border-start border-warning border-5">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-warning-subtle text-warning me-3">
                        <i class="bi bi-fire fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">En Preparación</h6>
                        <h2 class="fw-bold mb-0"><?php echo $dataTotalPreparando ?? 0; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white border-start border-success border-5">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-success-subtle text-success me-3">
                        <i class="bi bi-egg-fried fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Platillos Activos</h6>
                        <h2 class="fw-bold mb-0"><?php echo $totalPlatillos ?? 0; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-info me-3 text-success">
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-dark">¡Cocina en Marcha!</span>
                        <small class="text-muted">Revisa la pestaña de <b>Pedidos Activos</b> para comenzar a despachar.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


