<?php
// core/router.php

require_once __DIR__ . '/../config/config.php';


function despacharRuta($pagina)
{

    // 1. EL DICCIONARIO: La lista oficial de habitaciones que SÍ existen
    $rutas = [
        'admin' => 'views/administrador/home_admin.php',
        'cocina' => 'views/cocina/home_cocina.php',
        'menu'   => 'views/cliente/menu.php',
        'escanear' => 'app/consultar_mesa.php',
        'error_scan' => 'views/error_qr.php',
        'logout' => 'app/cerrar_sesion.php',
        'dashboard_admin' => 'views/modulos/dashboard_admin.php',
        'gestionar_mesas' => 'views/modulos/gestionar_mesas.php',
        'gestion_estados' => 'views/modulos/gestion_estados.php',
        'gestion_productos' => 'views/modulos/gestion_productos.php',
        'gestion_categorias' => 'views/modulos/gestion_categorias.php',
        'gestion_usuarios' => 'views/modulos/gestion_usuarios.php',
        'gestion_roles' => 'views/modulos/gestion_roles.php',
    ];

    // ==========================================
    // REGLA 1: Si intenta ingresar a una ruta que no existe -> 404
    // ==========================================
    if (!array_key_exists($pagina, $rutas)) {
        return __DIR__ . '/../views/404.php';
    }


    // Definimos qué rutas NO necesitan sesión de empleado (Admin/Cocina)
    $rutas_publicas = ['escanear', 'error_scan'];


    // ==========================================
    // REGLA 2: La ruta sí existe, pero... ¿Tiene sesión iniciada?
    // ==========================================
    if (!in_array($pagina, $rutas_publicas)) {
        if (!isset($_SESSION['nombre_rol']) && !isset($_SESSION['uuid'])) {
            // ¡Alerta de intruso! Lo redireccionamos al login (la raíz)
            header("Location: " . RUTA_BASE . "?error=credenciales_invalidas");
            exit; // Detenemos la ejecución por seguridad
        }
    }

    // 3. Si sobrevivió a las dos reglas anteriores, significa que la ruta existe 
    // y el usuario tiene sesión. ¡Le devolvemos su vista!
    return __DIR__ . '/../' . $rutas[$pagina];
}
