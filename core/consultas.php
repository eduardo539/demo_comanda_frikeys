<?php

// core/consultas.php

function obtenerUsuarioPorUsername($PDO, $username)
{
    $stmt = $PDO->prepare("SELECT user_id, Nombre, usuario, passw, r.nombre_rol, e.estado
                            FROM usuario AS u
                            INNER JOIN roles AS r ON u.rol_id = r.rol_id
                            INNER JOIN estados AS e ON u.estado_gen_id = e.estado_gen_id
                            WHERE u.usuario = ?;");
    $stmt->execute([$username]);
    return $stmt->fetch();
}




function obtenerNumeroMesa($PDO, $uuid)
{
    $stmt = $PDO->prepare("SELECT mesa_id, nombre_mesa, uuid FROM mesa
                            WHERE uuid = ?;");
    $stmt->execute([$uuid]);
    return $stmt->fetch();
}




function obtenerTotalUsuarios($PDO)
{

    // Ejecutamos el conteo directamente en la base de datos
    $stmt = $PDO->query("SELECT COUNT(*) FROM usuario");

    // fetchColumn() devuelve directamente el valor de la primera columna (el conteo)
    return $stmt->fetchColumn();
}



function obtenerTotalCategorias($PDO)
{
    // Ejecutamos el conteo directamente en la base de datos
    $stmt = $PDO->query("SELECT COUNT(*) FROM categoria;");

    // fetchColumn() devuelve directamente el valor de la primera columna (el conteo)
    return $stmt->fetchColumn();
}



function obtenerTotalMesas($PDO)
{
    // Ejecutamos el conteo directamente en la base de datos
    $stmt = $PDO->query("SELECT COUNT(*) FROM mesa;");

    // fetchColumn() devuelve directamente el valor de la primera columna (el conteo)
    return $stmt->fetchColumn();
}



function obtenerTotalPlatillos($PDO)
{
    // Ejecutamos el conteo directamente en la base de datos
    $stmt = $PDO->query("SELECT COUNT(*) FROM productos;");

    // fetchColumn() devuelve directamente el valor de la primera columna (el conteo)
    return $stmt->fetchColumn();
}




//Consulta para obtener los datos de los usuarios
function obtenerDataUsuarios($PDO)
{
    try {
        $stmt = $PDO->query("SELECT u.user_id, u.Nombre, u.Apellidos, u.telefono, u.usuario, 
                                    r.rol_id, r.nombre_rol 
                             FROM usuario AS u 
                             INNER JOIN roles AS r ON u.rol_id = r.rol_id");

        // fetchAll devuelve un array con todos los usuarios encontrados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Retorna un array vacío si hay un error
    }
}

//Consulta para obtener los datos de los roles
function obtenerDataRoles($PDO)
{
    try {
        $stmt = $PDO->query("SELECT * FROM roles;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

//Consulta para obtener los datos de las mesas
function obtenerDataMesas($PDO)
{
    try {
        $stmt = $PDO->query("SELECT m.mesa_id, m.nombre_mesa, m.uuid, m.qr_img,
                                e.estado_gen_id, e.estado
                                FROM mesa AS m
                                INNER JOIN estados AS e ON m.estado_gen_id = e.estado_gen_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

//Consulta para obtener los datos del estado general
function obtenerDataEstado($PDO)
{
    try {
        $stmt = $PDO->query("SELECT * FROM estados;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

//Consulta para obtener los datos del estados de los platillos
function obtenerDataEstadoPlatillo($PDO)
{
    try {
        $stmt = $PDO->query("SELECT * FROM estado_pedido;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

//Consulta para obtener los datos de las categorias
function obtenerCategorias($PDO)
{
    try {
        $stmt = $PDO->query("SELECT * FROM categoria;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

//Consulta para obtener los datos de los platillo
function obtenerDataPlatillos($PDO)
{
    try {
        $stmt = $PDO->query("SELECT p.producto_id, p.nombre, p.descripcion, p.costo,
                                p.imagen, c.categoria_id, c.categoria,
                                e.estado_gen_id, e.estado
                                FROM productos AS p
                                INNER JOIN categoria AS c ON p.categoria_id = c.categoria_id
                                INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}




//obtener historial de los pedidos
// Función 1: Solo cuenta el total (Optimizado para miles de datos)
function contarTotalHistorial($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(*) FROM detalle_pedido");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

// Función 2: Obtiene solo el bloque de datos necesario (Paginado)
function obtenerHistorialPaginado($PDO, $limit, $offset)
{
    try {
        $sql = "SELECT dp.detalle_id, dp.folio, dp.fecha, 
                       p.nombre, p.descripcion, p.imagen, p.costo, 
                       dp.cantidad, dp.total, 
                       m.nombre_mesa, ep.estado_pedido 
                FROM detalle_pedido AS dp
                INNER JOIN productos AS p ON dp.producto_id = p.producto_id
                INNER JOIN mesa AS m ON dp.mesa_id = m.mesa_id
                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                ORDER BY dp.fecha DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $PDO->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}





//Consulta para obtener los datos del usuario
function obtenerDataPerfil($PDO, $id_usuario)
{
    try {
        $stmt = $PDO->prepare("SELECT u.user_id, u.Nombre, u.Apellidos, u.telefono, u.edad, u.usuario,
                                    r.nombre_rol, e.estado
                                    FROM usuario AS u
                                    INNER JOIN roles AS r ON u.rol_id = r.rol_id
                                    INNER JOIN estados AS e ON u.estado_gen_id = e.estado_gen_id
                                    WHERE u.user_id = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Importante para acceder por nombre de columna
    } catch (PDOException $e) {
        return [];
    }
}
