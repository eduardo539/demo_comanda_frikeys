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
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold" onclick="nuevoRol()">
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
                            <div class="role-icon-bg mx-auto mb-4 bg-dark text-white shadow-sm">
                                <img src="<?php echo RUTA_BASE; ?>public/assets/img/roles.png"
                                        alt="Icono Estado"
                                        style="width: 50px; height: 50px; object-fit: contain;">
                            </div>

                            <h3 class="fw-bold text-dark mb-1">
                                <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                            </h3>

                            <div class="d-flex justify-content-center gap-2 border-top pt-4">
                                <button class="btn btn-light-primary rounded-3 py-2 px-3"
                                    onclick="editarUsuario(<?php echo $rol['rol_id']; ?>)" title="Editar Perfil">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-light-danger rounded-3 py-2 px-3"
                                    onclick="eliminarUsuario(<?php echo $rol['rol_id']; ?>)" title="Dar de Baja">
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
</div>