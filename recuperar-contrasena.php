<?php
session_start();
include_once 'app/Olvidar-contrasena/controlador_recuperacion.php';

if (isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()) {
    header('Location: index.php');
    exit();
}

$mensajes = procesar_solicitud_recuperacion();

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <title>Recuperar Contraseña</title>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center">
            <form class="p-4 rounded shadow custom-border" style="width: 100%; max-width: 450px;" method="post" action="recuperar-contrasena.php" autocomplete="off">
                <h2 class="text-center mb-4">Recuperar Contraseña</h2>

                <?php if (!empty($mensajes['error'])): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($mensajes['error']); ?></div>
                <?php endif; ?>

                <?php if (!empty($mensajes['exito'])): ?>
                    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($mensajes['exito']); ?></div>
                <?php else: ?>
                    <div class="mb-4">
                        <label for="identificador" class="form-label">Usuario o Correo Electrónico</label>
                        <input type="text" class="form-control" id="identificador" name="identificador" required autofocus>
                        <div class="form-text">Te enviaremos un enlace para restaurar tu contraseña.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar Enlace</button>
                <?php endif; ?>

                <p class="text-center mt-4 mb-0"><a href="index.php">Volver al inicio de sesión</a></p>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
