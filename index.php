<?php
include_once "app/usuario/model.php";
session_start();

include_once "helpers/vars.php";

if(getvar("accion") === "login") {
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

            <div class="flex-grow-1 d-flex justify-content-center align-items-center">
                <form class="p-4 rounded shadow custom-border" id="main-form" method="post" autocomplete="off">
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
            </div>

        <?php else: ?>

            <div class="mt-4 d-flex flex-column justify-content-center align-items-center flex-grow-1">
                <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
                    <div class="card-body text-center p-4">
                        <h3 class="card-title mb-4">Mi Pase de Acceso</h3>
                        <?php
                            $mat = @$_SESSION["current_user"]->matricula;
                            $uid = @$_SESSION["current_user"]->id;
                            $fallback = @$_SESSION["current_user"]->getQrData();
                        ?>
                        <div id="qrcode" class="d-flex justify-content-center mb-3" data-matricula="<?php echo $mat; ?>" data-id="<?php echo $uid; ?>" data-fallback="<?php echo $fallback; ?>"></div>
                        <p id="qr-label" class="text-muted font-monospace mb-0"></p>
                    </div>
                </div>
            </div>

            <script src="assets/js/qr_generator.js"></script>
            <script src="assets/js/escaner.js"></script>

        <?php endif; ?>

    </main>

    <?php include 'templates/footer.php'; ?>

</body>
</html>
