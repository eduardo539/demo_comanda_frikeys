<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Acceso | Frikeys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --frikeys-blue: #4fc3d0;
            --frikeys-gold: #9e763b;
            --frikeys-dark: #2d1b0d;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 90%;
            text-align: center;
            border-top: 8px solid var(--frikeys-blue);
        }

        .icon-box {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
            animation: shake 0.5s ease-in-out infinite alternate;
        }

        .brand-name {
            font-family: 'Pacifico', cursive;
            color: var(--frikeys-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .error-title {
            font-weight: 700;
            color: var(--frikeys-dark);
            margin-bottom: 15px;
        }

        .error-text {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .btn-staff {
            background-color: var(--frikeys-gold);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 25px;
        }

        .btn-staff:hover {
            background-color: var(--frikeys-dark);
            color: white;
            transform: translateY(-3px);
        }

        @keyframes shake {
            from { transform: rotate(-5deg); }
            to { transform: rotate(5deg); }
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon-box">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        
        <h1 class="brand-name">Frikeys</h1>
        <h4 class="error-title">¡Ups! Código Inválido</h4>
        
        <p class="error-text">
            Lo sentimos, el código QR escaneado no es válido o ha expirado. 
            <br><br>
            <strong>Vuelva a escanear el codigo QR, en caso de no funcionar
                por favor, póngase en contacto con el personal del restaurante para recibir asistencia.</strong>
        </p>

    </div>

</body>
</html>