<?php

function actualizarEstadoPedidoPorFolio($PDO, $folio, $nuevoEstadoId)
{
    try {
        // Actualiza todos los registros en detalle_pedido que tengan ese folio
        $sql = $PDO->prepare("UPDATE detalle_pedido SET estado_id = ? WHERE folio = ?");
        return $sql->execute([$nuevoEstadoId, $folio]);
    } catch (PDOException $e) {
        return false;
    }
}



?>