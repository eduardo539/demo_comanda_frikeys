<?php

function registrarNewRol($PDO, $nombre) {
    $sql = "INSERT INTO roles (nombre_rol) VALUES (:nombre)";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':nombre' => $nombre]);
}

function registrarNewEstadoGen($PDO, $estadoGen) {
    $sql = "INSERT INTO estados(estado) VALUES (:estadoGen);";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':estadoGen' => $estadoGen]);
}

function registrarNewEstadoPlatillo($PDO, $estadoPlatillo) {
    $sql = "INSERT INTO estado_pedido(estado_pedido) VALUES (:estadoPlatillo)";
    $stmt = $PDO->prepare($sql);
    return $stmt->execute([':estadoPlatillo' => $estadoPlatillo]);
}

function registrarNewCategoria($PDO, $categria) {
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


?>