<?php
include 'seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';


$dataPlatillos = obtenerDataPlatillos($pdo);

$estado = obtenerDataEstado($pdo);
$categoria = obtenerCategorias($pdo);
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
            <button class="btn btn-primary btn-lg rounded-pill shadow px-5 py-3 fw-bold"
                data-bs-toggle="modal"
                data-bs-target="#modalNewPlatillo">
                <i class="bi bi-plus-circle-fill me-2"></i>Nueva Categoría
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
                <div class="col-12 col-md-6 col-xxl-4 category-fuertes">
                    <div class="card product-card border-0 shadow-lg overflow-hidden h-100 rounded-4">
                        <div class="row g-0 h-100">

                            <div class="col-5 bg-light d-flex align-items-center justify-content-center position-relative overflow-hidden" style="min-height: 200px;">
                                <?php
                                $rutaDB = $dp['imagen'];
                                $rutaNavegador = ltrim($rutaDB, '/. ');
                                $rutaFisica = __DIR__ . '/../../' . $rutaNavegador;

                                if (!empty($rutaDB) && file_exists($rutaFisica)): ?>
                                    <img src="<?php echo htmlspecialchars($rutaNavegador); ?>"
                                        alt="<?php echo htmlspecialchars($dp['nombre']); ?>"
                                        class="w-100 h-100 img-zoom"
                                        style="object-fit: cover; position: absolute; top: 0; left: 0;">
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="bi bi-image text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted small mb-0">SIN FOTO</p>
                                    </div>
                                <?php endif; ?>

                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75 backdrop-blur px-3 py-2 rounded-pill shadow-sm">
                                        $ <?php echo htmlspecialchars($dp['costo']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-7">
                                <div class="card-body p-4 d-flex flex-column h-100">
                                    <div class="mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold" style="font-size: 0.7rem;">
                                            <i class="bi bi-tag-fill me-1"></i><?php echo htmlspecialchars($dp['categoria']); ?>
                                        </span>
                                    </div>

                                    <h4 class="fw-extrabold text-dark mb-2"><?php echo htmlspecialchars($dp['nombre']); ?></h4>

                                    <p class="text-muted small flex-grow-1 mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                        <?php echo htmlspecialchars($dp['descripcion']); ?>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 1px;">Acciones</small>
                                        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                            <button class="btn btn-sm btn-white border-end px-3" onclick="editarProducto(<?php echo $dp['producto_id']; ?>)" title="Editar">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </button>
                                            <button class="btn btn-sm btn-white px-3" onclick="eliminarProducto(<?php echo $dp['producto_id']; ?>)" title="Eliminar">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-clipboard-x text-muted opacity-25" style="font-size: 4rem;"></i>
                <p class="text-muted fs-5 mt-3">No hay platillos en esta categoría.</p>
            </div>
        <?php endif; ?>
    </div>






    <div class="modal fade" id="modalNewPlatillo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 justify-content-center pt-4">
                    <div class="text-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-egg-fried fs-2"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark px-3">Agregar Nuevo Platillo</h5>
                        <p class="text-muted small">Configura los detalles del menú</p>
                    </div>
                </div>

                <div class="modal-body p-4 pt-2">
                    <form id="formNuevoPlatillo" action="addNewPlatillo" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Nombre del Platillo</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-alphabet-uppercase"></i></span>
                                    <input type="text" name="nombre_platillo" class="form-control border-0 bg-white"
                                        placeholder="Ej: Tacos al Pastor" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                        title="Solo letras y espacios">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Costo ($)</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" name="costo" class="form-control border-0 bg-white"
                                        placeholder="0.00" step="0.01" min="0" required>
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Categoría</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-tag"></i></span>
                                    <select name="id_categoria" class="form-select border-0 bg-white fw-semibold" required>
                                        <option value="" selected disabled>Seleccione categoría...</option>
                                        <?php foreach ($categoria as $cat): ?>
                                            <option value="<?php echo $cat['categoria_id']; ?>">
                                                <?php echo htmlspecialchars($cat['categoria']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Descripción</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-card-text"></i></span>
                                    <input type="text" name="descripcion" class="form-control border-0 bg-white"
                                        placeholder="Ej: Orden de 5 tacos con piña" required
                                        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ0-9\s]+"
                                        title="Letras, números y espacios permitidos">
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Estado de Disponibilidad</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-check2-circle"></i></span>
                                    <select name="id_estado" class="form-select border-0 bg-white fw-semibold" required>
                                        <option value="" selected disabled>Seleccione estado...</option>
                                        <?php foreach ($estado as $e): ?>
                                            <option value="<?php echo $e['estado_gen_id']; ?>"><?php echo htmlspecialchars($e['estado']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <label class="form-label small fw-bold text-muted text-uppercase mb-1">Imagen del Platillo (Opcional)</label>
                                <div class="input-group border-bottom border-warning border-2 mb-3 shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-warning"><i class="bi bi-image"></i></span>
                                    <input type="file" name="imagen_platillo" id="imagen_platillo"
                                        class="form-control border-0 bg-white"
                                        accept=".png, .jpg, .jpeg"
                                        onchange="validarImagen(this)">
                                </div>
                                <small class="text-muted" style="font-size: 0.7rem;">Solo PNG, JPG, JPEG. Máximo 2MB.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning text-white py-3 fw-bold text-uppercase rounded-3 shadow border-0">
                                <i class="bi bi-plus-circle-fill me-2"></i>Guardar Platillo
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