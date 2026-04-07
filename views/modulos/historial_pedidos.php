<?php
include 'seguridad_modulo.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

// 1. Capturar filtros
$filtroFolio = isset($_GET['folio']) ? trim($_GET['folio']) : null;

// 2. Configuración de paginación
$registrosPorPagina = 40;
$paginaActual = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// 3. Obtención de datos con filtro
$totalRegistros = contarTotalHistorial($pdo, $filtroFolio);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$dataHistorial = obtenerHistorialPaginado($pdo, $registrosPorPagina, $offset, $filtroFolio);

// Construir query string para mantener el filtro al cambiar de página
$queryString = $filtroFolio ? "&folio=" . urlencode($filtroFolio) : "";
?>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white py-4 border-0">
            <div class="row g-3 align-items-center">
                <div class="col-xl-3 col-lg-4">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-receipt-cutoff me-2 text-dark"></i>Historial de Ventas
                    </h5>
                    <p class="text-muted small mb-0">Total: <b><?php echo number_format($totalRegistros); ?></b> líneas registradas</p>
                </div>

                <div class="col-xl-4 col-lg-4">
                    <form method="GET" class="position-relative">
                        <input type="text" name="folio" class="form-control ps-5 border-2 rounded-pill"
                            placeholder="Buscar por folio..." value="<?php echo htmlspecialchars($filtroFolio); ?>">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <?php if ($filtroFolio): ?>
                            <a href="?" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 text-decoration-none text-danger">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="col-xl-5 col-lg-4 d-flex justify-content-lg-end justify-content-center">
                    <div class="btn-group shadow-sm">
                        <a href="?p=<?php echo $paginaActual - 1 . $queryString; ?>"
                            class="btn btn-white border <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-white border-top border-bottom rounded-0 dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                                Pág. <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?>
                            </button>
                            <ul class="dropdown-menu shadow border-0" style="max-height: 300px; overflow-y: auto;">
                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li><a class="dropdown-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>"
                                            href="?p=<?php echo $i . $queryString; ?>">Página <?php echo $i; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>

                        <a href="?p=<?php echo $paginaActual + 1 . $queryString; ?>"
                            class="btn btn-success <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            Sig. 40 <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 border-0 py-3">Folio</th>
                        <th class="border-0">Fecha y Hora</th>
                        <th class="border-0">Mesa</th>
                        <th class="border-0">Producto</th>
                        <th class="border-0">Imagen</th>
                        <th class="border-0">Precio Unit.</th>
                        <th class="border-0 text-center">Cant.</th>
                        <th class="border-0">Total</th>
                        <th class="pe-4 border-0 text-end">Estado</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (!empty($dataHistorial)): ?>
                        <?php foreach ($dataHistorial as $reg): ?>
                            <tr style="cursor: pointer;" onclick="verDetalle('<?php echo $reg['folio']; ?>')">
                                <td class="ps-4">
                                    <span class="fw-bold text-dark font-monospace"><?php echo $reg['folio']; ?></span>
                                </td>
                                <td class="text-nowrap text-muted">
                                    <i class="bi bi-calendar-event me-1"></i><?php echo date('d/m/y', strtotime($reg['fecha'])); ?>
                                    <span class="small opacity-75 ms-1"><?php echo date('H:i', strtotime($reg['fecha'])); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-success border rounded-pill">
                                        <i class="bi bi-geo-alt-fill me-1"></i><?php echo $reg['nombre_mesa']; ?>
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark"><?php echo $reg['nombre']; ?></td>
                                <td>
                                    <img src="<?php echo RUTA_BASE . ltrim($reg['imagen'], '/. '); ?>"
                                        class="rounded shadow-sm border" width="35" height="35" style="object-fit: cover;"
                                        onerror="this.src='<?php echo RUTA_BASE; ?>public/img_public/default.png'">
                                </td>
                                <td>$<?php echo number_format($reg['costo'], 2); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-dark rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                        <?php echo $reg['cantidad']; ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-success">$<?php echo number_format($reg['total'], 2); ?></td>
                                <td class="pe-4 text-end">
                                    <?php
                                    $status = strtolower($reg['estado_pedido']);
                                    $color = (str_contains($status, 'pagado') || str_contains($status, 'entregado')) ? 'success' : 'warning';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> border border-<?php echo $color; ?> rounded-pill px-3 py-2">
                                        <?php echo strtoupper($reg['estado_pedido']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-search fs-1 text-muted opacity-25"></i>
                                <p class="mt-3 text-muted">No se encontraron resultados para "<b><?php echo $filtroFolio; ?></b>"</p>
                                <a href="?" class="btn btn-sm btn-outline-primary rounded-pill">Ver todo el historial</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>