<?php
include 'seguridad_modulo.php';


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$idExcluir = $_SESSION['user_id'];


$usuarios = obtenerDataUsuarios($pdo, $idExcluir);
$roles = obtenerDataRoles($pdo);
$estado = obtenerDataEstado($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-people-fill text-dark me-3"></i>
                Gestión de Empleados
            </h1>
            <p class="text-muted fs-5 mt-2">Administra el personal de tu restaurante y asigna sus permisos en el sistema.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-success btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNewUsuario">
                <i class="bi bi-person-plus-fill me-2"></i>Agregar Nuevo Usuario
            </button>
        </div>
    </div>


    <div class="row g-4" id="grid-usuarios">
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $user): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card user-card border-0 shadow-lg h-100 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-wrapper mx-auto mb-3 bg-success text-white shadow">
                                <span class="fs-2 fw-bold">
                                    <?php
                                    $inicialNombre = substr($user['Nombre'], 0, 1);
                                    $inicialApellido = substr($user['Apellidos'], 0, 1);
                                    echo strtoupper($inicialNombre . $inicialApellido);
                                    ?>
                                </span>
                            </div>

                            <h4 class="fw-bold text-dark mb-1">
                                <?php echo htmlspecialchars($user['Nombre'] . " " . $user['Apellidos']); ?>
                            </h4>

                            <div class="mb-3">
                                <span class="badge rounded-pill px-3 py-2 bg-dark text-white">
                                    <?php echo htmlspecialchars($user['nombre_rol']); ?>
                                </span>
                            </div>

                            <div class="mb-3">
                                <span class="badge rounded-pill px-3 py-2 bg-light text-dark border shadow-sm" style="font-weight: 500;">
                                    <?php echo htmlspecialchars($user['estado']); ?>
                                </span>
                            </div>

                            <div class="user-info text-muted small mb-4">
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <i class="bi bi-person-badge me-2"></i> Usuario: <?php echo htmlspecialchars($user['usuario']); ?>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-telephone me-2"></i> Telefono: <?php echo htmlspecialchars($user['telefono']); ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2 border-top pt-4">
                                <button class="btn btn-light-primary rounded-3 py-2 px-3"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"
                                    data-usuario="<?php echo $user['user_id']; ?>"
                                    title="Editar Perfil">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-light-warning rounded-3 py-2 px-3 text-warning"
                                    data-bs-toggle="modal" data-bs-target="#modalRestablecerPass"
                                    data-usuario="<?php echo $user['user_id']; ?>"
                                    title="Restablecer Contraseña">
                                    <i class="bi bi-key-fill"></i>
                                </button>

                                <button class="btn btn-light-danger rounded-3 py-2 px-3"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario"
                                    data-usuario="<?php echo $user['user_id']; ?>"
                                    title="Eliminar Usuario">
                                    <i class="bi bi-person-x-fill"></i>
                                </button>
                            </div>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay empleados registrados actualmente.</p>
            </div>
        <?php endif; ?>
    </div>











    <div class="modal fade" id="modalNewUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-plus-fill fs-2"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Registro de Nuevo Usuario</h5>
                        <p class="text-muted small">Todos los campos son obligatorios</p>
                    </div>
                </div>

                <div class="modal-body p-4 pt-2">
                    <form id="formNuevoUsuario" action="addNewUsuario" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Nombre</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control border-0 bg-white"
                                        placeholder="Ej: Juan Pablo" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                        title="Solo se permiten letras y espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Usuario (Login)</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-at"></i></span>
                                    <input type="text" name="usuario" class="form-control border-0 bg-white"
                                        placeholder="juan123" required
                                        pattern="[A-Za-z0-9]+"
                                        onkeypress="return event.charCode !== 32"
                                        title="Solo letras y números, sin espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Teléfono</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telefono" class="form-control border-0 bg-white"
                                        placeholder="987654321" required
                                        pattern="\d+" maxlength="10"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        title="Solo números, sin espacios">
                                </div>
                            </div>

                            <div class="col-md-6">

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Apellidos</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" name="apellidos" class="form-control border-0 bg-white"
                                        placeholder="Ej: Pérez García" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                        title="Solo se permiten letras y espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Edad</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" name="edad" class="form-control border-0 bg-white"
                                        placeholder="25" required
                                        maxlength="3"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        title="Solo números, máximo 3 dígitos">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Rol de Acceso</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-shield-lock"></i></span>
                                    <select name="id_rol" class="form-select border-0 bg-white fw-semibold" required>
                                        <option value="" selected disabled>Elija un rol...</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?php echo $rol['rol_id']; ?>">
                                                <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>



                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Estado del Usuario</label>
                                <div class="input-group border-bottom border-success border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-success">
                                        <i class="bi bi-toggle-on"></i> </span>
                                    <select name="id_estado" class="form-select border-0 bg-white fw-semibold" required>
                                        <option value="" selected disabled>Elija un estado...</option>
                                        <?php foreach ($estado as $e): ?>
                                            <option value="<?php echo $e['estado_gen_id']; ?>">
                                                <?php echo htmlspecialchars($e['estado']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success py-3 fw-bold text-uppercase rounded-3 shadow border-0">
                                <i class="bi bi-person-check-fill me-2"></i>Registrar Usuario
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none small fw-bold py-2" data-bs-dismiss="modal">
                                CANCELAR
                            </button>
                        </div>


                        <div class="mt-4 p-3 bg-light border-start border-success border-4 rounded shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-success fs-5 me-3"></i>
                                <div>
                                    <span class="text-dark fw-bold d-block small">Nota de Seguridad:</span>
                                    <span class="text-muted small">La contraseña de acceso por defecto será: <strong class="text-success">cambio123</strong></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
















    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Perfil de Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="actualizarEstadoUser" method="POST">
                    <div class="modal-body px-4">
                        <input type="hidden" name="user_id" id="input_usuario_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Nombre(s)</label>
                                <input type="text" id="view_nombre" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Apellidos</label>
                                <input type="text" id="view_apellidos" class="form-control bg-light" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Nombre de Usuario</label>
                                <input type="text" id="view_username" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Teléfono</label>
                                <input type="text" id="view_telefono" class="form-control bg-light" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Rol asignado</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock"></i></span>
                                    <input type="text" id="view_rol" class="form-control bg-light border-start-0" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-success">Estado de la cuenta (Editable)</label>
                            <select name="estado_id" id="edit_estado_user" class="form-select border-success shadow-sm" required>
                                <?php foreach ($estado as $est): ?>
                                    <option value="<?php echo $est['estado_gen_id']; ?>"><?php echo $est['estado']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text mt-2" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle"></i> Solo el estado de actividad puede ser modificado desde este panel.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRestablecerPass" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="" method="POST">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock-fill text-warning" style="font-size: 3rem;"></i>
                        </div>

                        <h5 class="fw-bold">Seguridad de Cuenta</h5>
                        <p class="text-muted small">¿Confirmas restablecer la contraseña para:<br><strong id="display_view_nombre" class="text-dark"></strong>?</p>

                        <input type="hidden" name="user_id" id="input_usuario_id">

                        <div class="alert alert-warning border-0 shadow-sm text-start py-3" style="background-color: #fffbeb;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <span class="fw-bold small">Información importante:</span>
                            </div>
                            <p class="mb-2 small">La clave se restablecerá a:</p>
                            <div class="text-center mb-2">
                                <code class="fs-5 fw-bold text-dark px-3 py-1 bg-white rounded border">cambio123</code>
                            </div>
                            <p class="mb-0 small text-muted italic" style="font-size: 0.7rem;">
                                * El usuario deberá actualizarla obligatoriamente al iniciar sesión por motivos de seguridad.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 d-flex gap-2 pb-4">
                        <button type="button" class="btn btn-light flex-grow-1 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning flex-grow-1 fw-bold shadow-sm">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="deleteUsuario" method="POST">
                    <div class="modal-body p-4 text-center">
                        <div class="bg-light-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #fee2e2;">
                            <i class="bi bi-person-dash-fill text-danger fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Eliminar Cuenta de Usuario</h5>
                        <p class="text-muted small">Usuario: <strong id="display_view_nombre"></strong></p>

                        <div class="alert alert-danger border-0 text-start small mb-0" style="background-color: #fef2f2;">
                            <p class="mb-2"><strong><i class="bi bi-exclamation-octagon-fill me-1"></i> Política de Seguridad:</strong></p>
                            <p class="mb-0">Solo se puede eliminar un registro si el usuario <strong>no tiene actividad registrada</strong> (ventas, compras, movimientos). Si el usuario ya operó en el sistema, la acción se convertirá automáticamente en <strong>"Inactivar Cuenta"</strong> para preservar el historial de auditoría.</p>
                        </div>

                        <input type="hidden" name="user_id" id="input_usuario_id">
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                        <button type="button" class="btn btn-light flex-grow-1 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger flex-grow-1 fw-bold shadow">Confirmar Acción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>