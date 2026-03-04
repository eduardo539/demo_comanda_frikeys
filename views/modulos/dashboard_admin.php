<?php
require 'seguridad_modulo.php'; 


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';


$totalUsuarios = obtenerTotalUsuarios($pdo);
$totalCategorias = obtenerTotalCategorias($pdo);
$totalMesas = obtenerTotalMesas($pdo);
$totalPlatillos = obtenerTotalPlatillos($pdo);
?>

<div class="container-fluid">
    <div class="row mb-5">
        <div class="col-12 text-center text-lg-start">
            <h2 class="fw-bold m-0 text-dark">DASHBOARD</h2>
            <p class="text-secondary">Panel inicial Sistema FriKeys</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="text-center">
                    <div class="icon-circle bg-primary-subtle text-primary mx-auto mb-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Usuarios / Empleados</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalUsuarios ?? 0; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="text-center">
                    <div class="icon-circle bg-info-subtle text-info mx-auto mb-3">
                        <i class="bi bi-tags-fill fs-3"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Categorías</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalCategorias ?? 0; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="text-center">
                    <div class="icon-circle bg-success-subtle text-success mx-auto mb-3">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Mesas</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalMesas ?? 0; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="text-center">
                    <div class="icon-circle bg-warning-subtle text-warning mx-auto mb-3">
                        <i class="bi bi-egg-fried fs-3"></i>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Platillos</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalPlatillos ?? 0; ?></h2>
                </div>
            </div>
        </div>
    </div>

    
    </div>
</div>