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
        /* --- ESTILOS BASE --- */
        body, html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        /* --- FONDO CON OVERLAY OSCURO --- */
        .bg-404 {
            /* Imagen de un plato vacío o cocina oscura */
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), 
                        url('https://images.unsplash.com/photo-1584315565803-6d5e53912205?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- TARJETA GLASSMORPHISM --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 4rem 3rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            color: white;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.5s ease-out;
        }

        /* --- TEXTOS Y BOTÓN --- */
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #f97316; /* Naranja del proyecto */
            line-height: 1;
            margin-bottom: 0;
            text-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        }

        .icon-404 {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 1rem;
            display: block;
        }

        .btn-home {
            background: #f97316;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 1.5rem;
        }

        .btn-home:hover {
            background: #ea580c;
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
            color: white;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            
            <a href="index.php" class="btn-home">
                <i class="bi bi-arrow-left-short fs-5 me-1 align-middle"></i> Volver al Inicio
            </a>

        </div>
    </div>

</body>
</html>