<?php
include_once __DIR__ . "/../init.php";

startAPI("otro.restaturar_contraseña");

include_once "app/Olvidar-contrasena/controlador_olvide_mi_contrasena.php";

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
