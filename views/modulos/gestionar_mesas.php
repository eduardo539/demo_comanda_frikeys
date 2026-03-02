<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$dataMesas = obtenerDataMesas($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-grid-3x3-gap-fill text-primary me-3"></i>
                Gestión de Mesas
            </h1>
            <p class="text-muted fs-5 mt-2">
                Bienvenido al centro operativo. Aquí puedes dar de alta y editar las mesas de tu restaurante.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold" onclick="nuevaMesa()">
                <i class="bi bi-plus-circle-fill me-2"></i>Nueva Mesa
            </button>
        </div>
    </div>

    <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mb-5 d-flex align-items-center">
        <div class="icon-info me-4 bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
            <i class="bi bi-info-circle-fill fs-3"></i>
        </div>
        <div>
            <span class="d-block fw-bold text-dark">Panel de Configuración</span>
            <small class="text-muted">Utiliza las tarjetas inferiores para gestionar cada unidad. Los cambios se reflejarán en tiempo real en el sistema de pedidos.</small>
        </div>
    </div>

    <div class="row g-4" id="grid-mesas">

        <?php if (!empty($dataMesas)): ?>
            <?php foreach ($dataMesas as $mesa): ?>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card mesa-card border-0 shadow-lg h-100 p-3">
                        <div class="card-body text-center">
                            <div class="mesa-icon-wrapper mx-auto mb-4">
                                <i class="bi bi-shop-window text-primary"></i>
                            </div>

                            <h3 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($mesa['nombre_mesa']); ?></h3>

                            <div class="d-flex justify-content-center gap-2 border-top pt-4">
                                <button class="btn btn-light-primary rounded-3 py-2 px-3"
                                    onclick="editarUsuario(<?php echo $mesa['mesa_id']; ?>)" title="Editar Perfil">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-light-danger rounded-3 py-2 px-3"
                                    onclick="eliminarUsuario(<?php echo $mesa['mesa_id']; ?>)" title="Dar de Baja">
                                    <i class="bi bi-person-x-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay Mesas registradas actualmente.</p>
            </div>
        <?php endif; ?>


    </div>
</div>