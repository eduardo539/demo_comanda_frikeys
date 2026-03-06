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



function actualizarEstadoMesaxuuid($PDO, $estadoId, $uuid)
{

    try {
        // Actualiza lo que tengan ese uuid
        $sql = $PDO->prepare("UPDATE mesa
                                SET estado_gen_id = ?
                                WHERE UUID = ?");
        return $sql->execute([$estadoId, $uuid]);
    } catch (PDOException $e) {
        return false;
    }

}





function actualizarEstadoGen($PDO, $estadoId, $newEstado)
{

    try {
        $sql = $PDO->prepare("UPDATE estados
                                SET estado = ?
                                WHERE estado_gen_id = ?");
        return $sql->execute([$newEstado, $estadoId]);
    } catch (PDOException $e) {
        return false;
    }

}


function actualizarEstadoPlatillo($PDO, $platilloID, $newEstado)
{

    try {
        $sql = $PDO->prepare("UPDATE estado_pedido
                                SET estado_pedido = ?
                                WHERE estado_id = ?");
        return $sql->execute([$newEstado, $platilloID]);
    } catch (PDOException $e) {
        return false;
    }

}
?>