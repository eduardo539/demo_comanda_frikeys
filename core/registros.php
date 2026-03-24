<?php

function registrarNewRol($PDO, $nombre)
{
    $sql = "INSERT INTO roles (nombre_rol) VALUES (:nombre)";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':nombre' => $nombre]);
}

function registrarNewEstadoGen($PDO, $estadoGen)
{
    $sql = "INSERT INTO estados(estado) VALUES (:estadoGen);";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':estadoGen' => $estadoGen]);
}

function registrarNewEstadoPlatillo($PDO, $estadoPlatillo)
{
    $sql = "INSERT INTO estado_pedido(estado_pedido) VALUES (:estadoPlatillo)";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':estadoPlatillo' => $estadoPlatillo]);
}

function registrarNewCategoria($PDO, $categria)
{
    $sql = "INSERT INTO categoria(categoria) VALUES (:categria)";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':categria' => $categria]);
}


function registrarNewUsuario($PDO, $nombre, $apellido, $telefono, $edad, $usuario, $passw, $rol, $estado)
{
    $sql = "INSERT INTO usuario(Nombre, Apellidos, telefono, edad, usuario, passw, rol_id, estado_gen_id)
            VALUES (:nombre, :apellido, :telefono, :edad, :usuario, SHA2(:passw, 256), :rol, :estado)";

    $stmt = $PDO->prepare($sql);

    // Pasamos el array asociativo mapeando cada marcador con su variable
    return $stmt->execute([
        ':nombre'   => $nombre,
        ':apellido' => $apellido,
        ':telefono' => $telefono,
        ':edad'     => $edad,
        ':usuario'  => $usuario,
        ':passw'    => $passw,
        ':rol'      => $rol,
        ':estado'   => $estado
    ]);
}


function registrarNewPlatillo($PDO, $categoria, $nombre, $descripcion, $costo, $estado, $imagen)
{
    $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, costo, estado_gen_id, imagen)
            VALUES (:categoria, :nombre, :descripcion, :costo, :estado, :imagen)";

    $stmt = $PDO->prepare($sql);

    return $stmt->execute([
        ':categoria' => $categoria,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':costo' => $costo,
        ':estado' => $estado,
        ':imagen' => $imagen
    ]);
}




function registrarNewMesa($PDO, $nombre, $uuid, $qrimg, $estado)
{
    $sql = "INSERT INTO mesa(nombre_mesa, UUID, qr_img, estado_gen_id)
            VALUES (:nombre,:uuid,:qrimg, :estado);";

    $stmt = $PDO->prepare($sql);

    return $stmt->execute([
        ':nombre' => $nombre,
        ':uuid' => $uuid,
        ':qrimg' => $qrimg,
        ':estado' => $estado,
    ]);
}





function registrarVentaCompleta($PDO, $datosPreparados)
{
    try {
        if (empty($datosPreparados)) return ['success' => false, 'error' => 'No hay datos'];

        $PDO->beginTransaction();

        // 1. Construir la base de la consulta
        $sql = "INSERT INTO detalle_pedido (folio, fecha, producto_id, cantidad, total, mesa_id, estado_id) VALUES ";
        
        $valores = [];
        $placeholders = [];

        // 2. Crear los placeholders (?,?,?,?,?,?,?) por cada producto
        foreach ($datosPreparados as $index => $item) {
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?)";
            
            // Metemos los valores en orden al arreglo plano para PDO
            $valores[] = $item['folio'];
            $valores[] = $item['fecha'];
            $valores[] = $item['producto_id'];
            $valores[] = $item['cantidad'];
            $valores[] = $item['total'];
            $valores[] = $item['mesa_id'];
            $valores[] = $item['estado_id'];
        }

        // Unimos los placeholders con comas: (?,?,...), (?,?,...)
        $sql .= implode(', ', $placeholders);

        $stmt = $PDO->prepare($sql);
        $stmt->execute($valores);

        $PDO->commit();
        // Retornamos el folio del primer item (todos tienen el mismo)
        return ['success' => true, 'folio' => $datosPreparados[0]['folio']];

    } catch (Exception $e) {
        if ($PDO->inTransaction()) $PDO->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}