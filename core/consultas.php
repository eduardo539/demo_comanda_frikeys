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
    $stmt = $PDO->prepare("SELECT mesa_id, nombre_mesa FROM mesa
                            WHERE uuid = ?;");
    $stmt->execute([$uuid]);
    return $stmt->fetch();
}