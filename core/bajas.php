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





function eliminarProductoSelect($PDO, $idProducto)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM productos
                                WHERE producto_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$idProducto]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar el producto: " . $e->getMessage());
        return false;
    }
}




function eliminarCategoriaSelect($PDO, $categoriaID)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM categoria
                                WHERE categoria_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$categoriaID]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar la categoria: " . $e->getMessage());
        return false;
    }
}





function eliminarUsuarioSelect($PDO, $idUsuario)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM usuario
                                WHERE user_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$idUsuario]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar la categoria: " . $e->getMessage());
        return false;
    }
}




function eliminarRolSelect($PDO, $idRol)
{
    try {
        
        $sql = $PDO->prepare("DELETE FROM roles
                                WHERE rol_id = ?");

        // execute() devuelve true si la consulta se ejecutó correctamente
        return $sql->execute([$idRol]);
    } catch (PDOException $e) {
        // En caso de error (ej. llave foránea), devolvemos false
        error_log("Error al eliminar la categoria: " . $e->getMessage());
        return false;
    }
}