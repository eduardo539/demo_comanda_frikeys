<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';


$dataRoles = obtenerDataRoles($pdo);
?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-primary me-3"></i>
                Roles y Permisos
            </h1>
            <p class="text-muted fs-5 mt-2">Define los niveles de acceso y responsabilidades para el personal del restaurante.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNuevoRol">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Rol
            </button>
        </div>
    </div>

    <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mb-5 d-flex align-items-center border-start border-primary border-4">
        <div class="icon-info me-4 text-primary">
            <i class="bi bi-info-square-fill fs-2"></i>
        </div>
        <div>
            <span class="d-block fw-bold text-dark">Control de Acceso</span>
            <small class="text-muted">Cada rol determina qué módulos y acciones puede realizar un usuario dentro de la plataforma.</small>
        </div>
    </div>

    <div class="row g-4" id="grid-roles">

        <?php if (!empty($dataRoles)): ?>
            <?php foreach ($dataRoles as $rol): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card role-card border-0 shadow-lg h-100 p-2">
                        <div class="card-body text-center">
                            <div class="role-icon-bg mx-auto mb-4 bg-primary bg-opacity-10 text-white shadow-sm">
                                <img src="<?php echo RUTA_BASE; ?>public/assets/img/roles.png"
                                    alt="Icono Roles"
                                    style="width: 70px; height: 70px; object-fit: contain;">
                            </div>

                            <h3 class="fw-bold text-dark mb-1">
                                <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                            </h3>

                            <div class="d-flex justify-content-center gap-2 border-top pt-4">
                                <button type="button"
                                    class="btn btn-light-primary rounded-3 py-2 px-3 btn-editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarRol"
                                    data-rol="<?php echo $rol['rol_id']; ?>"
                                    title="Editar Rol">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-light-danger rounded-3 py-2 px-3 btn-eliminar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarRol"
                                    data-rol="<?php echo $rol['rol_id']; ?>"
                                    title="Eliminar Rol">
                                    <i class="bi bi-person-x-fill"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay Roles registrados actualmente.</p>
            </div>
        <?php endif; ?>

    </div>



    <div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-0" style="background: #f8f9fa;">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-plus fs-2"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Crear Nuevo Rol</h5>
                        <p class="text-muted small">Define un nuevo nivel de acceso</p>
                    </div>
                </div>

                <div class="modal-body p-4 pt-2">

                    <form id="formNuevoRol" action="addRol" method="POST">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted mb-2 ls-1">Nombre del Rol</label>
                            <div class="input-group border-bottom border-primary border-2 shadow-sm">
                                <span class="input-group-text bg-white border-0 text-primary">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text"
                                    name="nombre_rol"
                                    id="nombre_rol"
                                    class="form-control border-0 bg-white ps-0 py-2 fw-semibold"
                                    placeholder="Ej: Administrador"
                                    required
                                    autocomplete="off"
                                    pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+"
                                    oninvalid="this.setCustomValidity('Solo se permiten letras, sin espacios ni números')"
                                    oninput="this.setCustomValidity('')"
                                    title="Solo letras, sin espacios">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" id="btnGuardarRol" class="btn btn-primary py-2 fw-bold text-uppercase rounded-0 shadow-sm border-0" style="background: linear-gradient(45deg, #4fc3d0, #38b2ac);">
                                <i class="bi bi-check-lg me-2"></i>Guardar Rol
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none small fw-bold py-2" data-bs-dismiss="modal">
                                CANCELAR
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>










    <div class="modal fade" id="modalEditarRol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Editar Rol de Sistema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="actualizarRol" method="POST">
                    <div class="modal-body px-4">
                        <div class="alert alert-warning border-0 rounded-3 mb-4 shadow-sm" style="background-color: #fffbeb; border-left: 4px solid #f59e0b !important;">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                                <div class="small">
                                    <strong>Advertencia de Historial:</strong> Modificar el nombre de este rol afectará a todos los registros vinculados (usuarios, auditorías y ventas). Se recomienda realizar cambios solo para correcciones ortográficas menores.
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="rol_id" id="input_rol_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nombre del Rol</label>
                            <input type="text" name="nombre_rol" id="edit_nombre_rol" class="form-control form-control-lg fs-6" placeholder="Ej. Administrador, Cajero..." required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Actualizar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminarRol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4 justify-content-center">
                    <div class="rounded-circle p-3" style="background-color: #fee2e2; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-shield-x text-danger fs-3"></i>
                    </div>
                </div>
                <form action="eliminarRol" method="POST">
                    <div class="modal-body px-4 text-center">
                        <h5 class="fw-bold mb-2">¿Eliminar Rol?</h5>
                        <p class="text-muted small">Vas a eliminar el rol:<br><strong id="display_nombre_rol" class="text-dark"></strong></p>

                        <div class="alert alert-danger py-2 border-0 rounded-3 mb-0" style="background-color: #fef2f2;">
                            <p class="small mb-0 text-danger" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                Esta acción es <strong>irreversible</strong>. Si existen usuarios con este rol, la operación podría fallar o dejar cuentas sin acceso.
                            </p>
                        </div>

                        <input type="hidden" name="rol_id" id="input_rol_id">
                    </div>
                    <div class="modal-footer border-0 p-3 d-flex gap-2 pb-4">
                        <button type="button" class="btn btn-light flex-grow-1 border" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger flex-grow-1 shadow">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>