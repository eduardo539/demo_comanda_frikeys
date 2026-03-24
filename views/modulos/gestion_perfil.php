<?php
include 'seguridad_modulo.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

// Asumiendo que guardas el ID en la sesión al loguear
$id_usuario_sesion = $_SESSION['user_id'];
$dataPerfil = obtenerDataPerfil($pdo, $id_usuario_sesion);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-5 text-center position-relative">
                    <div class="position-absolute top-100 start-50 translate-middle">
                        <div class="bg-white p-1 rounded-circle shadow">
                            <img src="https://ui-avatars.com/api/?name=<?php echo $dataPerfil['Nombre'] . '+' . $dataPerfil['Apellidos']; ?>&size=128&background=random"
                                class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5 mt-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-0"><?php echo $dataPerfil['Nombre'] . " " . $dataPerfil['Apellidos']; ?></h3>
                        <span class="badge bg-info-subtle text-info px-3 rounded-pill"><?php echo $dataPerfil['nombre_rol']; ?></span>
                        <span class="badge bg-success-subtle text-success px-3 rounded-pill"><?php echo $dataPerfil['estado']; ?></span>
                    </div>

                    <form id="formActualizaPerfil" action="actualizaPerfil" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $dataPerfil['user_id']; ?>">

                        <div class="row g-3 px-md-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">Nombre(s)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control border-0 bg-light" value="<?php echo $dataPerfil['Nombre']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="apellidos" class="form-control border-0 bg-light" value="<?php echo $dataPerfil['Apellidos']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telefono" class="form-control border-0 bg-light" value="<?php echo $dataPerfil['telefono']; ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Edad</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar-event"></i></span>
                                    <input type="number" name="edad" class="form-control border-0 bg-light" value="<?php echo $dataPerfil['edad']; ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-at"></i></span>
                                    <input type="text" name="usuario" class="form-control border-0 bg-light" value="<?php echo $dataPerfil['usuario']; ?>">
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-grid d-md-flex justify-content-md-between gap-3">
                                    <button type="button" class="btn btn-outline-warning border-2 rounded-pill px-4 fw-bold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalNewPass">
                                        <i class="bi bi-key me-2"></i> Cambiar Contraseña
                                    </button>

                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">
                                        <i class="bi bi-check2-circle me-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>








    <div class="modal fade" id="modalNewPass" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-0" style="background: #f8f9fa;">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-lock fs-2"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Seguridad</h5>
                        <p class="text-muted small">Actualiza tu clave de acceso</p>
                    </div>
                </div>

                <div class="modal-body p-4 pt-2">
                    <form action="cambiarPasswUsuario" method="POST" id="formNewPass">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted mb-2 ls-1">Contraseña Actual</label>

                            <input type="hidden" name="user_id" value="<?php echo $dataPerfil['user_id']; ?>">

                            <div class="input-group border-bottom border-warning border-2 shadow-sm">
                                <span class="input-group-text bg-white border-0 text-warning">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="pass_actual" id="pass_actual"
                                    class="form-control border-0 bg-white ps-0 py-2 fw-semibold"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted mb-2 ls-1">Nueva Contraseña</label>
                            <div class="input-group border-bottom border-primary border-2 shadow-sm">
                                <span class="input-group-text bg-white border-0 text-primary">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" name="pass_nueva" id="pass_nueva"
                                    class="form-control border-0 bg-white ps-0 py-2 fw-semibold"
                                    placeholder="Mín. 8 caracteres" required>
                            </div>
                            <div id="msg-error-pass" class="small mt-1" style="min-height: 18px;"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted mb-2 ls-1">Confirmar Nueva</label>
                            <div class="input-group border-bottom border-primary border-2 shadow-sm">
                                <span class="input-group-text bg-white border-0 text-primary">
                                    <i class="bi bi-check-all"></i>
                                </span>
                                <input type="password" name="pass_confirmar" id="pass_confirmar"
                                    class="form-control border-0 bg-white ps-0 py-2 fw-semibold"
                                    placeholder="Repite la clave" required>
                            </div>
                            <div id="msg-error-confirm" class="small mt-1" style="min-height: 18px;"></div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" id="btnGuardarPass" disabled
                                class="btn btn-primary py-2 fw-bold text-uppercase rounded-0 shadow-sm border-0 opacity-50"
                                style="background: linear-gradient(45deg, #4fc3d0, #38b2ac);">
                                <i class="bi bi-arrow-repeat me-2"></i>Actualizar
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




