<?php
include 'seguridad_modulo.php';


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$usuarios = obtenerDataUsuarios($pdo);

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
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold" onclick="nuevoUsuario()">
                <i class="bi bi-person-plus-fill me-2"></i>Nuevo Empleado
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



</div>
