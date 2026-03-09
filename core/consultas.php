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



function obtenerUsuarioPorID($PDO, $userID)
{
    $stmt = $PDO->prepare("SELECT user_id, Nombre, usuario, passw
                            FROM usuario AS u
                            WHERE u.user_id = ? LIMIT 1");
    $stmt->execute([$userID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
function obtenerDataUsuarios($PDO, $idExcluir)
{
    try {
        // 1. Preparamos la consulta con el marcador '?'
        $sql = "SELECT u.user_id, u.Nombre, u.Apellidos, u.telefono, u.usuario, 
                       r.rol_id, r.nombre_rol, e.estado_gen_id, e.estado
                FROM usuario AS u 
                INNER JOIN roles AS r ON u.rol_id = r.rol_id
                INNER JOIN estados AS e ON u.estado_gen_id = e.estado_gen_id 
                WHERE u.user_id != ?";

        $stmt = $PDO->prepare($sql);

        // 2. Ejecutamos pasando el ID que no queremos mostrar (ej. el del usuario logueado)
        $stmt->execute([$idExcluir]);

        // Retorna todos los registros excepto el del ID proporcionado
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Es buena idea registrar el error para depuración si no es demo
        // error_log($e->getMessage());
        return [];
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
                                INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}



function obtenerDataPlatillosCliente($PDO)
{
    try {
        $stmt = $PDO->query("SELECT p.producto_id, p.nombre, p.descripcion, p.costo,
                                p.imagen, c.categoria_id, c.categoria,
                                e.estado_gen_id, e.estado
                                FROM productos AS p
                                INNER JOIN categoria AS c ON p.categoria_id = c.categoria_id
                                INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id
                                WHERE e.estado = 'ACTIVO'");
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




//consulta para contar los pedidos recibidos
function obtenerTotalRecibidos($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(DISTINCT (folio))
                                FROM detalle_pedido AS dp
                                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                                WHERE ep.estado_pedido = 'RECIBIDO';");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}
//consulta para contar los pedidos en preparacion
function obtenerTotalPreparando($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(DISTINCT (folio))
                                FROM detalle_pedido AS dp
                                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                                WHERE ep.estado_pedido = 'PREPARANDO';");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}
//consulta para contar los pedidos activos
function obtenerTotalActivos($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(DISTINCT (folio))
                                FROM detalle_pedido AS dp
                                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                                WHERE ep.estado_pedido IN('RECIBIDO','PREPARANDO');");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}


//Consultar pedidos activos
function obtenerPedidosCocina($PDO)
{
    try {
        // Quitamos el ; de adentro del string y usamos fetchAll
        $sql = "SELECT dp.folio, SUM(dp.cantidad) AS cantidad, SUM(dp.total) AS total,
                       m.nombre_mesa, ep.estado_pedido
                FROM detalle_pedido AS dp
                INNER JOIN mesa AS m ON dp.mesa_id = m.mesa_id
                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                WHERE ep.estado_pedido IN('RECIBIDO','PREPARANDO')
                GROUP BY dp.folio, dp.mesa_id, dp.estado_id
                ORDER BY dp.fecha ASC"; // Asegúrate que 'fecha' tenga el prefijo dp. si es de detalle_pedido

        $stmt = $PDO->query($sql);

        // Usamos fetchAll para traer TODOS los pedidos de la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // En caso de error, devolvemos un array vacío para que el foreach no rompa la página
        return [];
    }
}

//Obtener la cantidad de pedidos individuales por folio
function obtenerPedidoxFolio($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(DISTINCT dp.folio) AS total_pedidos
                                FROM detalle_pedido AS dp
                                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                                WHERE ep.estado_pedido = 'RECIBIDO';");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

//Obtener la cantidad de pedidos individuales por entregar
function obtenerPedidoxEntregar($PDO)
{
    try {
        $stmt = $PDO->query("SELECT COUNT(DISTINCT dp.folio) AS total_pedidos
                                FROM detalle_pedido AS dp
                                INNER JOIN estado_pedido AS ep ON dp.estado_id = ep.estado_id
                                WHERE ep.estado_pedido = 'PREPARANDO';");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

//Consultar detalles del pedido
function obtenerDetallePedido($PDO, $folio)
{
    try {
        // Quitamos el ; de adentro del string y usamos fetchAll
        $sql = $PDO->prepare("SELECT dp.detalle_id, dp.folio, p.nombre, p.descripcion,
                    dp.cantidad, dp.total
                    FROM detalle_pedido AS dp
                    INNER JOIN productos AS p ON dp.producto_id = p.producto_id
                    WHERE dp.folio = ?;");

        $sql->execute([$folio]);

        // Usamos fetchAll para traer TODOS los pedidos de la consulta
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // En caso de error, devolvemos un array vacío para que el foreach no rompa la página
        return [];
    }
}




//Consulta para obtener los datos de la mesa
function obtenerMesaSelect($PDO, $uuid)
{
    try {
        // Agregamos m.estado_gen_id a la selección
        $sql = $PDO->prepare("SELECT m.mesa_id, m.nombre_mesa, m.uuid, m.qr_img, e.estado_gen_id, e.estado
                                FROM mesa AS m
                                INNER JOIN estados AS e ON m.estado_gen_id = e.estado_gen_id
                                WHERE m.uuid = ?;"); // El ; al final del string es opcional en PDO

        $sql->execute([$uuid]);

        // fetchAll devuelve un array de filas
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}




function obtenerEstadoGenSelect($PDO, $idEstado)
{
    try {
        $sql = $PDO->prepare("SELECT estado_gen_id, estado FROM estados WHERE estado_gen_id = ? LIMIT 1");
        $sql->execute([$idEstado]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}

function obtenerEstadoPlatilloSelect($PDO, $idEstadoPlatillo)
{
    try {
        $sql = $PDO->prepare("SELECT estado_id, estado_pedido FROM estado_pedido WHERE estado_id = ? LIMIT 1");
        $sql->execute([$idEstadoPlatillo]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}



function dataProductoSelect($PDO, $idProducto)
{
    try {
        $sql = $PDO->prepare("SELECT c.categoria_id, c.categoria, p.producto_id, p.nombre, p.descripcion, p.costo,
                                p.imagen, e.estado_gen_id, e.estado
                                FROM productos AS p
                                INNER JOIN categoria AS c ON p.categoria_id = c.categoria_id
                                INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id
                                WHERE p.producto_id = ? LIMIT 1");
        $sql->execute([$idProducto]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}






function dataCategoriaSelect($PDO, $id_categoria)
{
    try {
        $sql = $PDO->prepare("SELECT * FROM categoria WHERE categoria_id = ? LIMIT 1");
        $sql->execute([$id_categoria]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}




function dataUsuarioSelect($PDO, $usuarioID)
{
    try {
        $sql = $PDO->prepare("SELECT u.user_id, u.Nombre, u.Apellidos, u.telefono, u.usuario,
r.rol_id, r.nombre_rol, e.estado_gen_id, e.estado
FROM usuario AS u
INNER JOIN roles AS r ON u.rol_id = r.rol_id
INNER JOIN estados AS e ON u.estado_gen_id = e.estado_gen_id
WHERE u.user_id = ? LIMIT 1;");
        $sql->execute([$usuarioID]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}





function dataRolSelect($PDO, $id_rol)
{
    try {
        $sql = $PDO->prepare("SELECT * FROM roles WHERE rol_id = ?");
        $sql->execute([$id_rol]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}






function dataProductosxCategoria($PDO, $id_categoria)
{
    try {
        $sql = $PDO->prepare("SELECT p.producto_id, p.nombre, p.descripcion, p.costo,
p.imagen, c.categoria_id, c.categoria,
e.estado_gen_id, e.estado
FROM productos AS p
INNER JOIN categoria AS c ON p.categoria_id = c.categoria_id
INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id
WHERE c.categoria_id = ? AND e.estado='ACTIVO'");
        $sql->execute([$id_categoria]);
        return $sql->fetch(PDO::FETCH_ASSOC); // Fila única
    } catch (PDOException $e) {
        return false;
    }
}
