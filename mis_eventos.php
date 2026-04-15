<?php
include_once __DIR__ . "/init.php";

startAPI("login");

include_once __DIR__ . '/app/mis_eventos/model.php';

$usuario_id = $_SESSION["current_user"]->id;
$pasaporte  = new MiPasaporte();

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container my-4">
        <?php include 'app/mis_eventos/ver.php'; ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
