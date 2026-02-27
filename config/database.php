<?php

// 1. Leemos el archivo .env que está en la carpeta anterior (la raíz)
$envPath = __DIR__ . '/../.env';


if (!file_exists($envPath)) {
    die("Error: Falta el archivo .env");
}

$env = parse_ini_file($envPath);


// 2. Extraemos las variables
$host = $env['DB_HOST'];
$db   = $env['DB_DATABASE'];
$user = $env['DB_USERNAME'];
$pass = $env['DB_PASSWORD'];
$port = $env['DB_PORT'] ?? 3306;


// 3. Creamos la conexión con PDO (La forma más segura en PHP)
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Muestra errores de SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays limpios
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Mayor seguridad
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $opciones);
    
} catch (PDOException $e) {
    die("Error de conexión a la Base de Datos: " . $e->getMessage());
}


?>