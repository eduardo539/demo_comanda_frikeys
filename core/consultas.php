<?php

// core/consultas.php

function obtenerUsuarioPorUsername($cnn, $username) {
    $stmt = $cnn->prepare("SELECT id, username, password, rol FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}


?>