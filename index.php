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
        <h1 class="mb-4">Pasaporte TICs 2026</h1>

        <?php if(!(isset($_SESSION["current_user"]) && $_SESSION["current_user"])): ?>

            <div class="flex-grow-1 d-flex justify-content-center align-items-center">
                <form class="p-4 border rounded shadow" id="main-form" method="post" autocomplete="off">
                    <h2 class="text-center mb-4">Acceso</h2>
                    <?php if(isset($err) && $err):?>
                        <div class="alert alert-danger" role="alert"><?php echo $err; ?></div>
                    <?php endif; ?>
                    <?php include "app/usuario/form_login.php"; ?>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>

        <?php else: ?>

            <div class="row gy-3" id="modulos-de-sistema">

            <?php if ($_SESSION["current_user"]->can("evento.*")): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-grid gap-2">
                    <a href="eventos.php" class="btn btn-outline-secondary">
                        <i class="fa-regular fa-calendar-days"></i>
                        Eventos
                    </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION["current_user"]->can("usuario.*")): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-grid gap-2">
                    <a href="usuarios.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-users"></i>
                        Usuarios
                    </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($_SESSION["current_user"]->can("perfil.*")): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-grid gap-2">
                    <a href="perfiles.php" class="btn btn-outline-secondary">
                        <span class="fa-stack" style="font-size: 0.7em;">
                            <i class="fa-brands fa-superpowers fa-stack-2x"></i>
                            <i class="fa-solid fa-users fa-stack-1x"></i>
                        </span>
                        Perfiles
                    </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION["current_user"]->can("permiso.*")): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-grid gap-2">
                    <a href="permisos.php" class="btn btn-outline-secondary">
                        <i class="fa-brands fa-superpowers"></i>
                        Permisos
                    </a>
                    </div>
                </div>
            <?php endif; ?>

            </div>
            <div class="mt-4 d-flex flex-column justify-content-center align-items-center flex-grow-1">
                <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
                    <div class="card-body text-center p-4">
                        <h3 class="card-title mb-4">Mi Pase de Acceso</h3>
                        <div id="qrcode" class="d-flex justify-content-center mb-3" data-text="<?php echo $_SESSION["current_user"]->getQrData(); ?>"></div>
                        <p class="text-muted font-monospace mb-0"><?php echo $_SESSION["current_user"]->getQrData(); ?></p>
                    </div>
                </div>
            </div>
            <script src="assets/js/qr_generator.js"></script>
        <?php endif; ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
