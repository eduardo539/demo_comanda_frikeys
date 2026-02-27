<?php
// views/cocina/home_cocina.php

// 1. Si intentan entrar directo al archivo, simplemente apagamos la página
if (!defined('RUTA_BASE')) {
    header("Location: ../../"); 
    exit;
}

// 2. Validación de rol
if (!isset($_SESSION['nombre_rol']) || $_SESSION['nombre_rol'] !== 'COCINA') {
    header("Location: " . RUTA_BASE);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
echo("Hola cocinero");
?>
    
</body>
</html>