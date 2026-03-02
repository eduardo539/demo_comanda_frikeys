<?php require 'seguridad_modulo.php'; ?>

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
                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Usuarios</h6>
                    <h2 class="fw-bold mb-0">8</h2>
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
                    <h2 class="fw-bold mb-0">12</h2>
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
                    <h2 class="fw-bold mb-0">15</h2>
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
                    <h2 class="fw-bold mb-0">45</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h5 class="fw-bold text-dark">
                <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Acciones Frecuentes
            </h5>
            <hr class="opacity-10">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-6 col-md-4 col-xl-3">
            <a href="#" class="text-decoration-none nav-link-ajax" data-modulo="mesas">
                <div class="card shortcut-card border-0 shadow-sm rounded-4 text-center p-4 h-100 bg-white">
                    <div class="shortcut-icon mx-auto mb-3">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Mesas</h6>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="#" class="text-decoration-none nav-link-ajax" data-modulo="platillos">
                <div class="card shortcut-card border-0 shadow-sm rounded-4 text-center p-4 h-100 bg-white">
                    <div class="shortcut-icon mx-auto mb-3 bg-info-subtle text-info">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Menú</h6>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="#" class="text-decoration-none nav-link-ajax" data-modulo="usuarios">
                <div class="card shortcut-card border-0 shadow-sm rounded-4 text-center p-4 h-100 bg-white">
                    <div class="shortcut-icon mx-auto mb-3 bg-dark-subtle text-dark">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Usuarios</h6>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="#" class="text-decoration-none nav-link-ajax" data-modulo="estados">
                <div class="card shortcut-card border-0 shadow-sm rounded-4 text-center p-4 h-100 bg-white">
                    <div class="shortcut-icon mx-auto mb-3 bg-danger-subtle text-danger">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Estados</h6>
                </div>
            </a>
        </div>
    </div>
</div>