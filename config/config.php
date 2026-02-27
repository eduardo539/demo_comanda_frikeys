<?php
// config/config.php

// 1. Obtenemos la raíz absoluta del servidor (ej. C:/xampp/htdocs o /public_html)
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// 2. Obtenemos la ruta física exacta donde está ESTE archivo config.php
$dir_actual = str_replace('\\', '/', __DIR__);

// 3. Subimos una carpeta para encontrar la raíz de TU proyecto
$directorio_proyecto = dirname($dir_actual);

// 4. Restamos la ruta del servidor a la ruta de tu proyecto para obtener el "GPS" web
$ruta_calculada = str_replace($doc_root, '', $directorio_proyecto);

// 5. Definimos la constante mágica
if ($ruta_calculada === '') {
    // Si la resta da vacío, significa que estás en la raíz del Hosting
    define('RUTA_BASE', '/');
} else {
    // Si sobra algo, es el nombre de tu carpeta en XAMPP
    define('RUTA_BASE', $ruta_calculada . '/');
}
?>