<?php

function eliminarMesaSelect($PDO, $uuidMesa)
{
    try {
        // Preparamos la sentencia para eliminar la fila que coincida con el UUID
        $sql = $PDO->prepare("DELETE FROM mesa WHERE uuid = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$uuidMesa]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar mesa: " . $e->getMessage());
        return false;
    }
}





function eliminarEstadoGenSelect($PDO, $estadoID)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM estados 
                                WHERE estado_gen_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$estadoID]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar el estado general: " . $e->getMessage());
        return false;
    }
}




function eliminarEstadoPlatilloSelect($PDO, $estadoID)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM estado_pedido 
                                WHERE estado_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$estadoID]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar el estado del platillo: " . $e->getMessage());
        return false;
    }
}