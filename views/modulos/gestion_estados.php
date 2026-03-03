<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';



$estadoGen = obtenerDataEstado($pdo);
$estadoPlatillo = obtenerDataEstadoPlatillo($pdo);

?>


<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-toggles2 text-primary me-3"></i>
                Panel de Estados
            </h1>
            <p class="text-muted fs-5 mt-2">Configuración de diccionarios para disponibilidad de entidades y flujo de procesos.</p>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center flex-grow-1">
                <h4 class="fw-bold text-primary text-uppercase tracking-wider m-0 me-3">Estados Generales</h4>
                <hr class="flex-grow-1 border-primary opacity-25 d-none d-md-block">
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold ms-3"
                data-bs-toggle="modal"
                data-bs-target="#modalNuevoEstadoGen">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Estado Gral.
            </button>
        </div>

        <div class="row g-4">
            <?php if (!empty($estadoGen)): ?>
                <?php foreach ($estadoGen as $gen): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card status-card border-0 shadow-lg h-100 p-3">
                            <div class="card-body text-center">
                                <div class="status-icon-wrapper mx-auto mb-3 bg-primary bg-opacity-10 text-primary">
                                    <img src="<?php echo RUTA_BASE; ?>public/assets/img/estados.png"
                                        alt="Icono Estado"
                                        style="width: 40px; height: 40px; object-fit: contain;">
                                </div>
                                <h4 class="fw-bold text-dark"><?php echo htmlspecialchars($gen['estado']); ?></h4>
                                <div class="d-flex justify-content-center gap-2 border-top pt-3 mt-3">
                                    <button class="btn-action-small btn-edit-status" onclick="editarEstadoGral(<?php echo $gen['estado_gen_id']; ?>)"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-action-small btn-delete-status" onclick="eliminarEstadoGral(<?php echo $gen['estado_gen_id']; ?>)"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No hay estados generales registrados actualmente.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center flex-grow-1">
                <h4 class="fw-bold text-info text-uppercase tracking-wider m-0 me-3">Estados de Pedidos</h4>
                <hr class="flex-grow-1 border-info opacity-25 d-none d-md-block">
            </div>
            <button class="btn btn-info text-white rounded-pill px-4 shadow-sm fw-bold ms-3"
                data-bs-toggle="modal"
                data-bs-target="#modalNuevoEstadoPedido">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Estado Pedido
            </button>
        </div>

        <div class="row g-4">
            <?php if (!empty($estadoPlatillo)): ?>
                <?php foreach ($estadoPlatillo as $platillo): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card status-card border-0 shadow-lg h-100 p-3">
                            <div class="card-body text-center">
                                <div class="status-icon-wrapper mx-auto mb-3 bg-info bg-opacity-10 text-info">
                                    <img src="<?php echo RUTA_BASE; ?>public/assets/img/estado_pedidos.png"
                                        alt="Icono Estado"
                                        style="width: 50px; height: 50px; object-fit: contain;">
                                </div>
                                <h4 class="fw-bold text-dark"><?php echo htmlspecialchars($platillo['estado_pedido']); ?></h4>
                                <div class="d-flex justify-content-center gap-2 border-top pt-3 mt-3">
                                    <button class="btn-action-small btn-edit-status" onclick="editarEstadoPedido(<?php echo $platillo['estado_id']; ?>)"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn-action-small btn-delete-status" onclick="eliminarEstadoPedido(<?php echo $platillo['estado_id']; ?>)"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No hay estados de platillos registrados actualmente.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>





    <div class="modal fade" id="modalNuevoEstadoGen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-toggles2 fs-3"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark">Nuevo Estado Gral.</h5>
                        <p class="text-muted small">Disponibilidad de entidades</p>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <form id="addEstadoGen" action="addEstadoGen" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nombre del Estado</label>
                            <input type="text"
                                name="estadoGen"
                                id="estadoGen"
                                class="form-control form-control-lg bg-light border-0 fs-6"
                                placeholder="Ej: Activo"
                                required
                                pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+"
                                title="Solo se permiten letras, sin espacios ni números"
                                oninvalid="this.setCustomValidity('Solo se permiten letras, sin espacios ni números')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2">GUARDAR ESTADO</button>
                            <button type="button" class="btn btn-light text-muted small fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalNuevoEstadoPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark">Nuevo Estado Pedido</h5>
                        <p class="text-muted small">Flujo del restaurante</p>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <form id="addEstadoPlatillo" action="addEstadoPlatillo" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nombre del Estado</label>
                            <input type="text"
                                name="estado_pedido"
                                id="estado_pedido"
                                class="form-control form-control-lg bg-light border-0 fs-6"
                                placeholder="Ej: Preparando"
                                required
                                pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+"
                                title="Solo se permiten letras, sin espacios ni números"
                                oninvalid="this.setCustomValidity('Solo se permiten letras, sin espacios ni números')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info text-white fw-bold py-2">GUARDAR ESTADO</button>
                            <button type="button" class="btn btn-light text-muted small fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




</div>