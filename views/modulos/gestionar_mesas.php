<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$dataMesas = obtenerDataMesas($pdo);
$estados = obtenerDataEstado($pdo);

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
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNewMesa">
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
                    <div class="card mesa-card border border-light-subtle shadow-sm h-100 p-3 rounded-4 bg-light bg-opacity-75">
                        <div class="card-body text-center">

                            <div class="mesa-icon-wrapper mx-auto mb-4 d-flex align-items-center justify-content-center bg-white shadow-sm rounded-4"
                                style="width: 180px; height: 180px; border: 1px solid #f0f0f0;">
                                <?php
                                // Asegúrate de que el índice sea el correcto según tu base de datos ('qr_img' o 'imagen')
                                $rutaDB = $mesa['qr_img'];
                                $rutaNavegador = ltrim($rutaDB, '/. ');
                                $rutaFisica = __DIR__ . '/../../' . $rutaNavegador;

                                if (!empty($rutaDB) && file_exists($rutaFisica)): ?>
                                    <img src="<?php echo htmlspecialchars($rutaNavegador); ?>"
                                        alt="QR Mesa"
                                        class="img-fluid p-2"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="bi bi-qr-code text-muted opacity-25" style="font-size: 4rem;"></i>
                                        <p class="small text-muted mb-0">Sin QR</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h3 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($mesa['nombre_mesa']); ?></h3>

                            <span class="badge rounded-pill bg-opacity-10 mb-3 <?php echo ($mesa['estado_gen_id'] == 1) ? 'bg-success text-success' : 'bg-danger text-danger'; ?>">
                                <?php echo htmlspecialchars($mesa['estado']); ?>
                            </span>

                            <div class="d-flex justify-content-center gap-2 border-top border-secondary-subtle pt-4">
                                <button class="btn btn-white shadow-sm text-primary rounded-3 py-2 px-3 border btn-editar-mesa"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarMesa"
                                    data-uuid="<?php echo $mesa['uuid']; ?>"
                                    title="Editar Mesa">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-white shadow-sm text-danger rounded-3 py-2 px-3 border"
                                    onclick="eliminarMesa(<?php echo $mesa['uuid']; ?>)" title="Eliminar Mesa">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-layout-three-columns fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-2">No hay Mesas registradas actualmente.</p>
            </div>
        <?php endif; ?>
    </div>








    <div class="modal fade" id="modalNewMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-grid-3x3-gap-fill fs-2"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Registrar Nueva Mesa</h5>
                    </div>
                </div>

                <div class="modal-body p-4">
                    <form id="formNuevaMesa" action="addNewMesa" method="POST" enctype="multipart/form-data">

                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Identificador / Nombre</label>
                        <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                            <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-hash"></i></span>
                            <input type="text" name="nombre_mesa" class="form-control border-0 bg-white"
                                placeholder="Ej: Mesa 15 VIP" required
                                pattern="[A-Za-z0-9\sñÑáéíóúÁÉÍÓÚ]+"
                                title="Solo letras, números y espacios">
                        </div>

                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Estado Inicial</label>
                        <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                            <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-toggle-on"></i></span>
                            <select name="id_estado" class="form-select border-0 bg-white fw-semibold" required>
                                <option value="" selected disabled>Seleccione estado...</option>
                                <?php foreach ($estados as $est): ?>
                                    <option value="<?php echo $est['estado_gen_id']; ?>"><?php echo htmlspecialchars($est['estado']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary py-3 fw-bold text-uppercase rounded-3 shadow border-0">
                                <i class="bi bi-plus-circle me-2"></i>Guardar Mesa
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none small fw-bold" data-bs-dismiss="modal">
                                CANCELAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







    <div class="modal fade" id="modalEditarMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 bg-primary bg-gradient text-white p-4 rounded-top-4">
                    <h5 class="modal-title fw-bold m-0">Configurar Mesa</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="actualizarMesa" method="POST">
                    <div class="modal-body p-4">
                        <input type="hidden" name="uuid" id="input_uuid">

                        <div class="mb-4 text-center">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Mesa Seleccionada</span>
                            <div class="py-2 px-3 bg-light rounded-pill border d-inline-block">
                                <h4 id="display_nombre_mesa" class="fw-bold">---</h4>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">NUEVO ESTADO</label>
                            <select name="estado" id="select_estado" class="form-select fw-semibold">
                                <?php foreach ($estados as $est): ?>
                                    <option value="<?php echo $est['estado_gen_id']; ?>">
                                        <?php echo htmlspecialchars($est['estado']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





</div>