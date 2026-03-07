<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Pasaporte TICs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    
    <div class="card shadow p-4 my-4" style="max-width: 500px; width: 100%;">
        <h3 class="text-center mb-4">Crear Cuenta</h3>
        
        <form action="procesar.php" method="POST">
            <input type="hidden" name="accion" value="registro">
            
            <div class="mb-3">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Matrícula</label>
                <input type="text" name="matricula" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Grupo</label>
                <input type="text" name="grupo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Registrarse</button>
        </form>

        <div class="mt-3 text-center">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>

</body>
</html>