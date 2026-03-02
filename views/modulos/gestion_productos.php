<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';


$dataPlatillos = obtenerDataPlatillos($pdo);
?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-egg-fried text-primary me-3"></i>
                Gestión de Menú
            </h1>
            <p class="text-muted fs-5 mt-2">Configura los platillos y bebidas que ofreces a tus clientes.</p>
        </div>
        <div class="col-md-5 text-md-end">
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold" onclick="nuevoProducto()">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Platillo
            </button>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                        <i class="bi bi-filter-right fs-4"></i>
                    </div>
                    <span class="fw-bold text-dark fs-5">Filtrar por Categoría</span>
                </div>

                <div class="col-md-4">
                    <select class="form-select form-select-lg border-2 rounded-3" onchange="filtrarCategoria(this.value)">
                        <option value="todos" selected>Ver Todo el Menú</option>
                        <option value="entradas">Entradas</option>
                        <option value="fuertes">Platos Fuertes</option>
                        <option value="bebidas">Bebidas</option>
                        <option value="postres">Postres</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" id="grid-productos">

        <?php if (!empty($dataPlatillos)): ?>
            <?php foreach ($dataPlatillos as $dp): ?>

                <div class="col-12 col-md-6 col-xl-4 category-fuertes">
                    <div class="card product-card border-0 shadow-lg overflow-hidden h-100">
                        <div class="row g-0 h-100">
                            <div class="col-4 bg-light d-flex align-items-center justify-content-center position-relative">
                                <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                                <div class="price-tag"> $ <?php echo htmlspecialchars($dp['costo']); ?></div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-4 d-flex flex-column h-100">
                                    <div class="mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3"><?php echo htmlspecialchars($dp['categoria']); ?></span>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($dp['nombre']); ?></h4>
                                    <p class="text-muted small flex-grow-1 text-truncate-2"><?php echo htmlspecialchars($dp['descripcion']); ?></p>
                                    <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-2">
                                        <button class="btn btn-sm btn-light text-primary rounded-3 py-2 px-3" onclick="editarProducto(<?php echo $dp['producto_id']; ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light text-danger rounded-3 py-2 px-3" onclick="eliminarProducto(<?php echo $dp['producto_id']; ?>)">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay platillos registrados actualmente.</p>
            </div>
        <?php endif; ?>


    </div>
</div>