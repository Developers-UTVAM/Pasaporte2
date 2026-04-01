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

        <h1 class="mb-4"><span class="colores-gay big-text">Bienvenido <?php echo $_SESSION["current_user"] ?? "Lobo"; ?>!!</span></h1>

        <?php if(!(isset($_SESSION["current_user"]) && $_SESSION["current_user"])): ?>

            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center py-4">

                <form class="p-4 rounded shadow custom-border w-100" style="max-width: 400px;" id="main-form" method="post" autocomplete="off">
                    <h2 class="text-center mb-4">Acceso</h2>
                    <?php if(isset($err) && $err):?>
                        <div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
                    <?php endif; ?>
                    <?php include "app/usuario/form_login.php"; ?>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>

                    <div class="d-flex flex-column gap-2 mt-4 text-center">
                        <small class="text-muted">
                            ¿No tienes una cuenta? <a href="registro.php" class="text-decoration-none fw-bold">Regístrate aquí</a>
                        </small>
                        <small class="text-muted">
                            ¿Olvidaste tu contraseña? <a href="recuperar-contrasena.php" class="text-decoration-none fw-bold">Recupérala aquí</a>
                        </small>
                    </div>
                </form>
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
