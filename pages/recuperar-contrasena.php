<?php
include_once __DIR__ . "/../init.php";

startAPI();

include_once 'app/Olvidar-contrasena/controlador_recuperacion.php';

if (isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()) {
    header('Location: ' . ROOT_URL . 'index.php');
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

    <main class="container flex-grow-1 d-flex flex-column py-4">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center pb-5">
            <form class="card p-4 p-md-5 shadow-lg w-100 animate-on-load delay-1" style="max-width: 450px; border-radius: 20px; background: rgba(15, 15, 20, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08);" method="post" action="recuperar-contrasena.php" autocomplete="off">
                <div class="text-center mb-5">
                    <h2 class="fw-black mb-1" style="color:#fff; font-size:1.75rem; letter-spacing:-0.04em;">Recuperar Acceso</h2>
                    <p class="small m-0" style="color:rgba(255,255,255,0.45); letter-spacing:0.01em;">Te enviaremos un enlace a tu correo</p>
                </div>

                <?php if (!empty($mensajes['error'])): ?>
                    <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;"><i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($mensajes['error']); ?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button></div>
                <?php endif; ?>

                <?php if (!empty($mensajes['exito'])): ?>
                    <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.3); color: var(--color-green-400); border-radius: 16px;"><i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($mensajes['exito']); ?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button></div>
                <?php else: ?>
                    <div class="mb-4">
                        <label for="identificador" class="form-label text-light opacity-75">Usuario o Correo Electrónico</label>
                        <input type="text" class="form-control" id="identificador" name="identificador" required autofocus>
                        <div class="form-text" style="color: rgba(255,255,255,0.5);">Te enviaremos un enlace para restaurar tu contraseña.</div>
                    </div>
                    <button type="submit" class="btn btn-action-gradient w-100 py-3"><i class="fa-solid fa-envelope-open-text me-2"></i> Enviar Enlace</button>
                <?php endif; ?>

                <p class="text-center mt-4 mb-0"><a href="<?php echo ROOT_URL; ?>index.php" class="text-decoration-none fw-bold" style="color: var(--primary); text-shadow: 0 0 8px color-mix(in oklab, var(--primary) 40%, transparent);"><i class="fa-solid fa-arrow-left me-1"></i> Volver al inicio de sesión</a></p>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
