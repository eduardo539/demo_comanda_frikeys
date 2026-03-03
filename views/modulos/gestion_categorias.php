<?php
include 'seguridad_modulo.php';


require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';


$dataCategorias = obtenerCategorias($pdo);

?>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-tags-fill text-primary me-3"></i>
                Categorías del Menú
            </h1>
            <p class="text-muted fs-5 mt-2">Organiza tus productos en grupos para facilitar la navegación de tus clientes.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNewCategoria">
                <i class="bi bi-plus-circle-fill me-2"></i>Nueva Categoría
            </button>
        </div>
    </div>

    <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mb-5 d-flex align-items-center">
        <div class="icon-info me-4 bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
            <i class="bi bi-grid-fill fs-3"></i>
        </div>
        <div>
            <span class="d-block fw-bold text-dark">Estructura del Menú</span>
            <small class="text-muted">Las categorías activas aparecerán como filtros en la sección de productos y en el menú digital.</small>
        </div>
    </div>

    <div class="row g-4" id="grid-categorias">

        <?php if (!empty($dataCategorias)): ?>
            <?php foreach ($dataCategorias as $cat): ?>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card category-card border-0 shadow-lg h-100 p-3 text-center">
                        <div class="card-body">
                            <div class="category-icon-wrapper mx-auto mb-4 bg-primary bg-opacity-10 text-primary">
                                <img src="<?php echo RUTA_BASE; ?>public/assets/img/categoria.png"
                                    alt="Icono Categoria"
                                    style="width: 40px; height: 40px; object-fit: contain;">
                            </div>

                            <h3 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($cat['categoria']); ?></h3>

                            <div class="d-flex justify-content-center gap-3 border-top pt-4">
                                <button class="btn btn-action-cat btn-edit-cat" onclick="editarCategoria(1)" title="Editar Nombre/Icono">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-action-cat btn-delete-cat" onclick="eliminarCategoria(1)" title="Eliminar Categoría">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay categorias registrados actualmente.</p>
            </div>
        <?php endif; ?>



    </div>







    <div class="modal fade" id="modalNewCategoria" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-tags-fill fs-3"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Nueva Categoría</h5>
                        <p class="text-muted small">Organiza tus platillos en el menú</p>
                    </div>
                </div>

                <div class="modal-body p-4 pt-2">
                    <form id="addNuevaCategoria" action="addNuevaCategoria" method="POST">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted mb-2 ls-1">Nombre de Categoría</label>
                            <div class="input-group border-bottom border-success border-2 shadow-sm">
                                <span class="input-group-text bg-white border-0 text-success">
                                    <i class="bi bi-egg-fried"></i>
                                </span>
                                <input type="text"
                                    name="nombre_categoria"
                                    id="nombre_categoria"
                                    class="form-control border-0 bg-white ps-0 py-2 fw-semibold"
                                    placeholder="Ej: Entradas"
                                    required
                                    autocomplete="off"
                                    pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+"
                                    oninvalid="this.setCustomValidity('Solo se permiten letras, sin espacios ni números')"
                                    oninput="this.setCustomValidity('')"
                                    title="Solo letras, sin espacios">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success py-2 fw-bold text-uppercase rounded-3 shadow-sm border-0">
                                <i class="bi bi-check-lg me-2"></i>Guardar Categoría
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