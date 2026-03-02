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
    try{
        $stmt = $PDO->query("SELECT * FROM roles;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}

//Consulta para obtener los datos de las mesas
function obtenerDataMesas($PDO)
{
    try{
        $stmt = $PDO->query("SELECT * FROM mesa;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}

//Consulta para obtener los datos del estado general
function obtenerDataEstado($PDO)
{
    try{
        $stmt = $PDO->query("SELECT * FROM estados;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}

//Consulta para obtener los datos del estados de los platillos
function obtenerDataEstadoPlatillo($PDO)
{
    try{
        $stmt = $PDO->query("SELECT * FROM estado_pedido;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}

//Consulta para obtener los datos de las categorias
function obtenerCategorias($PDO)
{
    try{
        $stmt = $PDO->query("SELECT * FROM categoria;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}

//Consulta para obtener los datos de los platillo
function obtenerDataPlatillos($PDO)
{
    try{
        $stmt = $PDO->query("SELECT p.producto_id, p.nombre, p.descripcion, p.costo,
                                p.imagen, c.categoria_id, c.categoria,
                                e.estado_gen_id, e.estado
                                FROM productos AS p
                                INNER JOIN categoria AS c ON p.categoria_id = c.categoria_id
                                INNER JOIN estados AS e ON p.estado_gen_id = e.estado_gen_id;");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        return[];
    }
}