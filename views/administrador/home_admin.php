<?php

// Si no existe la constante, significa que intentaron entrar escribiendo la ruta
// de la carpeta directamente sin pasar por el index.php
if (!defined('RUTA_BASE')) {
    header("Location: ../../"); 
    exit;
}

// Si NO hay sesión iniciada O el rol NO es el de Administrador
if (!isset($_SESSION['nombre_rol']) || $_SESSION['nombre_rol'] !== 'ADMINISTRADOR') {
    header("Location: " . RUTA_BASE);
    exit;
}



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | FriKeys</title>
</head>

<body>
    <h1>Bienvenido, <?php echo $_SESSION['Nombre']; ?></h1>
    <p>Este es tu panel de control de administrador.</p>
</body>

</html>