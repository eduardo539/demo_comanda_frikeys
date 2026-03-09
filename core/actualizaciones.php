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


function actualizaProductos($PDO, $categoriaID, $nombre, $descripcion, $costo, $estadoID, $productoID)
{
    try {
        $sql = $PDO->prepare("UPDATE productos
SET categoria_id = ?, nombre = ?, descripcion = ?, costo = ?,estado_gen_id = ?
WHERE producto_id = ?;");
        return $sql->execute([$categoriaID, $nombre, $descripcion, $costo, $estadoID, $productoID]);
    } catch (PDOException $e) {
        return false;
    }
}




function actualizaCategoria($PDO, $newCategoria, $categoriaID)
{
    try {
        $sql = $PDO->prepare("UPDATE categoria
SET categoria = ?
WHERE categoria_id = ?;");
        return $sql->execute([$newCategoria, $categoriaID]);
    } catch (PDOException $e) {
        return false;
    }
}



function actualizaEstadoUser($PDO, $estado, $userID)
{
    try {
        $sql = $PDO->prepare("UPDATE usuario SET estado_gen_id = ? WHERE user_id = ?");
        return $sql->execute([$estado, $userID]);
    } catch (PDOException $e) {
        return false;
    }
}


function resetPassUser($PDO, $pass_reset, $userID)
{
    try {
        $sql = $PDO->prepare("UPDATE usuario SET passw = SHA2(?, 256) WHERE user_id = ?");
        return $sql->execute([$pass_reset, $userID]);
    } catch (PDOException $e) {
        return false;
    }
}




function actualizaRol($PDO, $newRol, $rolID)
{
    try {
        $sql = $PDO->prepare("UPDATE roles SET nombre_rol = ? WHERE rol_id = ?");
        return $sql->execute([$newRol, $rolID]);
    } catch (PDOException $e) {
        return false;
    }
}




function actualizaPassUsuario($PDO, $nuevoPass, $idUser)
{
    try {
        $sql = $PDO->prepare("UPDATE usuario SET passw = ? WHERE user_id = ?");
        return $sql->execute([$nuevoPass, $idUser]);
    } catch (PDOException $e) {
        return false;
    }
}




function actualizaUsuarioPerfil($PDO, $nombre, $apellidos, $telefono, $edad, $usuario, $userID)
{
    try {
        $sql = $PDO->prepare("UPDATE usuario
SET Nombre = ?, Apellidos = ?, telefono = ?, edad = ?, usuario = ?
WHERE user_id = ?");
        return $sql->execute([$nombre, $apellidos, $telefono, $edad, $usuario, $userID]);
    } catch (PDOException $e) {
        return false;
    }
}
