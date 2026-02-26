<?php

// core/consultas.php

function obtenerUsuarioPorUsername($PDO, $username) {
    $stmt = $PDO->prepare("SELECT id, username, password, rol FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}


?>