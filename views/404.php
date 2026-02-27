<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- 1. RESET Y CONFIGURACIÓN BASE --- */
        body,
        html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Evita scroll innecesario */
        }

        /* --- 2. CONTENEDOR PRINCIPAL (FONDO) --- */
        .bg-404 {
            /* Gradiente oscuro + Imagen de fondo */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)),
                url('https://images.unsplash.com/photo-1584315565803-6d5e53912205?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- 3. TARJETA GLASSMORPHISM --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 4rem 3rem;
            width: 90%;
            max-width: 500px;
            text-align: center;
            color: white;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.6s ease-out;
        }

        /* --- 4. ELEMENTOS VISUALES --- */
        .icon-404 {
            font-size: 4.5rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 1rem;
            display: block;
            /* Animación sutil de flotado */
            animation: float 3s ease-in-out infinite;
        }

        .error-code {
            font-size: 7rem;
            font-weight: 800;
            color: #f97316;
            /* Naranja corporativo */
            line-height: 0.9;
            margin: 0;
            text-shadow: 0 4px 20px rgba(249, 115, 22, 0.5);
        }

        /* --- 5. BOTÓN DE ACCIÓN --- */
        .btn-home {
            background: #f97316;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem 2.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-home:hover {
            background: #ea580c;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.4);
            color: white;
        }

        /* --- 6. ANIMACIONES --- */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        /* --- 7. RESPONSIVE --- */
        @media (max-width: 576px) {
            .glass-card {
                padding: 2.5rem 1.5rem;
            }

            .error-code {
                font-size: 5rem;
            }
        }
    </style>

</head>

<body>

    <div class="bg-404">
        <div class="glass-card">

            <i class="bi bi-egg-fried icon-404"></i>

            <h1 class="error-code">404</h1>
            <h3 class="fw-bold mt-3">¡Plato no encontrado!</h3>

            <p class="text-white-50 mt-3 mb-4">
                Parece que la página que buscas se quemó en el horno o el mesero se equivocó de mesa.
            </p>

            <a href="<?php echo RUTA_BASE; ?>index.php" class="btn-home">
                <i class="bi bi-arrow-left-short fs-5 me-1 align-middle"></i> Volver al Inicio
            </a>

        </div>
    </div>

</body>

</html>