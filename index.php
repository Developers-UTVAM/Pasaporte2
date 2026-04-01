<?php
include_once __DIR__ . "/init.php";

startAPI();

if(checkVar("accion", "login")) {
    $username = getvar("username");
    $password = getvar("password");
    if($username && $password) {
        $usr = new Usuario();
        if(!$usr->authenticate($username, $password)) {
            $err = "Error al accesar al sistema: usuario o contraseña no válidos";
        }
    }
}
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <?php if(!(isset($_SESSION["current_user"]) && $_SESSION["current_user"])): ?>
            <div class="text-center mt-4 mb-2">
                <h1 class="colores-gay big-text mb-2" style="font-weight: 900; line-height: 1.1; letter-spacing: -1px;">Bienvenido a la Semana de TICs 2026</h1>
                <p class="fs-5 mt-3" style="color: var(--text-color); opacity: 0.8;">Inicia sesión para acceder a tu pasaporte digital</p>
            </div>

            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center py-4">

                <div class="card p-4 shadow-lg w-100" style="max-width: 400px; border-radius: 24px;">
                    <form id="main-form" method="post" autocomplete="off">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-user-lock mb-3" style="font-size: 3.5rem; color: var(--primary); filter: drop-shadow(0 0 15px color-mix(in oklab, var(--primary) 50%, transparent));"></i>
                            <h2 class="mb-0 card-title">Acceso</h2>
                            <p class="mt-2" style="color: var(--text-color); opacity: 0.7;">Ingresa tus credenciales para continuar</p>
                        </div>

                        <?php if(isset($err) && $err):?>
                            <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
                                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $err; ?>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php include "app/usuario/form_login.php"; ?>

                        <button type="submit" class="btn btn-action-gradient w-100 mt-3 py-3"><i class="fa-solid fa-right-to-bracket me-2"></i> Entrar</button>

                        <div class="d-flex flex-column gap-3 mt-4 text-center">
                            <div>
                                <small style="color: var(--text-color); opacity: 0.7;">¿No tienes una cuenta?</small><br>
                                <a href="registro.php" class="text-decoration-none fw-bold" style="color: var(--primary);"><i class="fa-solid fa-user-plus me-1"></i> Regístrate aquí</a>
                            </div>
                            <div>
                                <small style="color: var(--text-color); opacity: 0.7;">¿Olvidaste tu contraseña?</small><br>
                                <a href="recuperar-contrasena.php" class="text-decoration-none fw-bold" style="color: var(--secondary);"><i class="fa-solid fa-key me-1"></i> Recupérala aquí</a>
                            </div>
                        </div>
                    </form>
                </div>
<div class="w-100 mt-5 mb-5" style="max-width: 800px;">

    <h4 class="text-center mb-4" style="color: var(--text-color); font-weight: var(--font-weight-light); opacity: 0.8;">
        Descubre nuestras últimas actividades
    </h4>

    <!-- <div class="card p-3 mb-4" style="border-radius: 24px;">
        <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script>
        <iframe src="//lightwidget.com/widgets/ec85b02092e35b879334c0f3b5a05c69.html"
                scrolling="no"
                allowtransparency="true"
                class="lightwidget-widget"
                style="width:100%; border:0; overflow:hidden;">
        </iframe>
    </div> -->

    <div class="text-center mt-4">
        <a href="https://www.instagram.com/cybervibe_2026/" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
            <i class="fab fa-instagram me-2"></i> Síguenos
        </a>
    </div>

</div>

        <?php else: ?>

            <h1 class="mb-4"><span class="colores-gay big-text">Bienvenido <?php echo htmlspecialchars((string)($_SESSION["current_user"] ?? '')); ?>!!</span></h1>
            <div class="mt-4 d-flex flex-column justify-content-center align-items-center flex-grow-1 w-100">
                <div class="card shadow-lg" style="max-width: 400px; width: 100%; border-radius: 24px; overflow: hidden;">
                    <div class="text-center p-3" style="background: linear-gradient(135deg, color-mix(in oklab, var(--primary) 20%, transparent), transparent); border-bottom: 1px solid var(--glass-border);">
                        <h3 class="card-title m-0" style="font-size: 1.5rem;">Mi Pase de Acceso</h3>
                        <p class="mb-0 mt-1 text-uppercase" style="color: var(--text-color); font-weight: bold; letter-spacing: 1px; opacity: 0.9;">
                            <?php echo htmlspecialchars((string)($_SESSION["current_user"] ?? 'Usuario')); ?>
                        </p>
                    </div>

                    <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                        <?php
                            $mat = @$_SESSION["current_user"]->matricula;
                            $uid = @$_SESSION["current_user"]->id;
                            $fallback = @$_SESSION["current_user"]->getQrData();
                        ?>
                        <div id="qrcode" class="d-flex justify-content-center mb-4 position-relative" data-matricula="<?php echo $mat; ?>" data-id="<?php echo $uid; ?>" data-fallback="<?php echo $fallback; ?>"></div>
                        <h4 id="qr-label" class="m-0 font-monospace text-primary fw-bold mb-2" style="letter-spacing: 2px;"></h4>
                        <small style="color: var(--text-color); opacity: 0.6;"><i class="fa-solid fa-expand me-1"></i> Muestra este código en la entrada</small>
                    </div>
                </div>
            </div>
            <script src="assets/js/qr_generator.js"></script>
            <div>
            </div>
        <?php endif; ?>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
