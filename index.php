<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión de Incapacidades</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <h1>Iniciar Sesión</h1>

            <form id="loginForm">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" id="usuario" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" id="contrasena" required>
                </div>

                <button type="submit">Ingresar</button>

                <p id="mensaje"></p>
            </form>
        </div>
    </div>

    <script src="assets/js/auth.js"></script>

</body>
</html>