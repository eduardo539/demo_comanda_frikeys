<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/assets/css/login.css">
</head>
<body>

    <div class="bg-login">
        <div class="glass-card">
            
            <div class="text-center mb-4">
                <img src="public/assets/img/logo.jpg" alt="Logo Restaurante" class="brand-logo">
                <h3 class="fw-bold mt-2">Login FriKeys</h3>
                <p class="text-white-50 small">Panel de Administración y Cocina</p>
            </div>

            <form action="app/auth_login.php" method="POST">
                
                <div class="mb-4">
                    <label class="form-label small text-white-50 mb-1 ps-1">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="user" class="form-control border-start-0 ps-0" placeholder="Ej. admin" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small text-white-50 mb-1 ps-1">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="pass" class="form-control border-start-0 border-end-0 ps-0" id="passwordInput" placeholder="••••••••" required>
                        <span class="input-group-text border-start-0" id="togglePassword">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 mt-2">
                    Ingresar <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/js/login.js"></script>
</body>
</html>