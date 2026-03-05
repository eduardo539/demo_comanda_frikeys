<?php
include __DIR__ . '/../modulos/seguridad_modulo.php';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/consultas.php';

$folio = $_GET['folio'] ?? '';
$estado = trim($_GET['estado'] ?? ''); // El estado que viene del botón "Ver Comanda"

if (empty($folio)) {
    echo "<div class='alert alert-danger'>Error: Folio no recibido.</div>";
    exit;
}

$detalles = obtenerDetallePedido($pdo, $folio);
$totalFinal = 0;
?>

<?php if (is_array($detalles) && !empty($detalles)): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary small">
                    <th>CANT.</th>
                    <th>PRODUCTO</th>
                    <th class="text-end">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item):
                    $subtotal = isset($item['total']) ? (float)$item['total'] : 0;
                    $totalFinal += $subtotal;
                ?>
                    <tr>
                        <td class="fw-bold text-primary"><?php echo $item['cantidad']; ?>x</td>
                        <td>
                            <span class="d-block fw-bold text-dark"><?php echo htmlspecialchars($item['nombre']); ?></span>
                            <small class="text-muted d-block" style="font-size: 0.8rem;"><?php echo htmlspecialchars($item['descripcion']); ?></small>
                        </td>
                        <td class="text-end fw-bold text-dark">$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="alert bg-light border-0 d-flex justify-content-between align-items-center mt-3 p-3 rounded-4 shadow-sm">
        <span class="fw-bold text-muted small text-uppercase">Total a Pagar:</span>
        <span class="fs-3 fw-bold text-primary">$<?php echo number_format($totalFinal, 2); ?></span>
    </div>

    <div class="mt-4">
        <?php if ($estado === 'RECIBIDO'): ?>
            <form action="updateEstadoPlatillo" method="POST">
                <input type="hidden" name="folio" value="<?php echo htmlspecialchars($folio); ?>">
                <input type="hidden" name="estado_id" value="2"> <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase">
                    <i class="bi bi-play-circle-fill me-2"></i> Comenzar a Preparar
                </button>
            </form>

        <?php elseif ($estado === 'PREPARANDO'): ?>
            <form action="updateEstadoPlatillo" method="POST">
                <input type="hidden" name="folio" value="<?php echo htmlspecialchars($folio); ?>">
                <input type="hidden" name="estado_id" value="3">

                <button type="submit"
                    class="btn btn-success w-100 py-3 fw-bold text-uppercase rounded-3 shadow"
                    style="background: linear-gradient(45deg, #198754, #20c997); border: none;">
                    <i class="bi bi-check-circle-fill me-2"></i> Terminar Pedido
                </button>
            </form>

        <?php else: ?>
            <div class="text-center p-2 rounded bg-light border">
                <span class="text-muted fw-bold small text-uppercase">
                    <i class="bi bi-info-circle me-1"></i> Pedido en estado: <?php echo $estado; ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

<?php else: ?>
    <div class="text-center py-4">
        <i class="bi bi-search fs-1 text-muted opacity-25"></i>
        <p class="mt-2 text-muted">No hay detalles para el folio: <?php echo htmlspecialchars($folio); ?></p>
    </div>
<?php endif; ?>