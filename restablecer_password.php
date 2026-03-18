<?php
session_start();
include_once 'app/Olvidar-contrasena/controlador_recuperacion.php';

if (isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()) {
    header('Location: index.php');
    exit();
}

$token = $_GET['token'] ?? '';
$mensajes = procesar_restablecimiento_password($token);

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <title>Restablecer Contraseña</title>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center">
            <form class="p-4 rounded shadow custom-border" style="width: 100%; max-width: 450px;" method="post" action="restablecer_password.php?token=<?php echo urlencode($token); ?>" autocomplete="off">
                <h2 class="text-center mb-4">Nueva Contraseña</h2>

                <?php if (!empty($mensajes['error'])): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($mensajes['error']); ?></div>
                <?php endif; ?>

                <?php if (!empty($mensajes['exito'])): ?>
                    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($mensajes['exito']); ?></div>
                    <p class="text-center mt-4 mb-0"><a href="index.php" class="btn btn-primary w-100">Ir a Iniciar Sesión</a></p>
                <?php else: ?>
                    <?php if ($mensajes['token_valido']): ?>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Contraseña</button>
                    <?php endif; ?>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
