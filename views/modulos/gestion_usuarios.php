<?php
include 'seguridad_modulo.php';


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$usuarios = obtenerDataUsuarios($pdo);
$roles = obtenerDataRoles($pdo);
$estado = obtenerDataEstado($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-people-fill text-primary me-3"></i>
                Gestión de Empleados
            </h1>
            <p class="text-muted fs-5 mt-2">Administra el personal de tu restaurante y asigna sus permisos en el sistema.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNewUsuario">
                <i class="bi bi-person-plus-fill me-2"></i>Nueva Categoría
            </button>
        </div>
    </div>


    <div class="row g-4" id="grid-usuarios">
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $user): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card user-card border-0 shadow-lg h-100 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <div class="avatar-wrapper mx-auto mb-3 bg-primary text-white shadow">
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
                                    onclick="editarUsuario(<?php echo $user['user_id']; ?>)" title="Editar Perfil">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-light-danger rounded-3 py-2 px-3"
                                    onclick="eliminarUsuario(<?php echo $user['user_id']; ?>)" title="Dar de Baja">
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
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
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
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control border-0 bg-white"
                                        placeholder="Ej: Juan Pablo" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                        title="Solo se permiten letras y espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Usuario (Login)</label>
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-at"></i></span>
                                    <input type="text" name="usuario" class="form-control border-0 bg-white"
                                        placeholder="juan123" required
                                        pattern="[A-Za-z0-9]+"
                                        onkeypress="return event.charCode !== 32"
                                        title="Solo letras y números, sin espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Teléfono</label>
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telefono" class="form-control border-0 bg-white"
                                        placeholder="987654321" required
                                        pattern="\d+" maxlength="10"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        title="Solo números, sin espacios">
                                </div>
                            </div>

                            <div class="col-md-6">

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Apellidos</label>
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" name="apellidos" class="form-control border-0 bg-white"
                                        placeholder="Ej: Pérez García" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                        title="Solo se permiten letras y espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Edad</label>
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" name="edad" class="form-control border-0 bg-white"
                                        placeholder="25" required
                                        maxlength="3"
                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                        title="Solo números, máximo 3 dígitos">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Rol de Acceso</label>
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary"><i class="bi bi-shield-lock"></i></span>
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
                                <div class="input-group border-bottom border-primary border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-primary">
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
                            <button type="submit" class="btn btn-primary py-3 fw-bold text-uppercase rounded-3 shadow border-0">
                                <i class="bi bi-person-check-fill me-2"></i>Registrar Usuario
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none small fw-bold py-2" data-bs-dismiss="modal">
                                CANCELAR
                            </button>
                        </div>


                        <div class="mt-4 p-3 bg-light border-start border-primary border-4 rounded shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-primary fs-5 me-3"></i>
                                <div>
                                    <span class="text-dark fw-bold d-block small">Nota de Seguridad:</span>
                                    <span class="text-muted small">La contraseña de acceso por defecto será: <strong class="text-primary">cambio123</strong></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





</div>