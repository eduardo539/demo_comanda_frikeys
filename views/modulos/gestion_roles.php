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
                                    data-id="<?php echo $rol['rol_id']; ?>"
                                    title="Editar Perfil">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button"
                                    class="btn btn-light-danger rounded-3 py-2 px-3 btn-eliminar"
                                    data-id="<?php echo $rol['rol_id']; ?>"
                                    title="Dar de Baja">
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



</div>
