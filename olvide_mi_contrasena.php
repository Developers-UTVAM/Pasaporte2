<?php
include_once "app/usuario/model.php";
session_start();

include_once "app/Olvidar-contrasena/controlador_olvide_mi_contrasena.php";
if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->is_authenticated()) {
    header("Location: index.php");
    exit();
}

$errors = procesar_cambio_password();

?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container mt-4">
        <?php include 'app/Olvidar-contrasena/vista_olvide_mi_contrasena.php'; ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
