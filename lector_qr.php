<?php

include_once "app/usuario/model.php";
include_once 'helpers/vars.php';
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <title>Testeo Lector QR</title>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container mt-4 flex-grow-1">
        <div class="card shadow custom-border border-warning">
            <div class="card-body text-center">
                <h2 class="mb-2">Lector de Códigos QR</h2>
                <p class="text-muted mb-4">Modo Testeo: Solo lectura y recuperación de datos</p>

                <button class="btn btn-primary mb-3" id="btn-toggle-camera" onclick="toggleLector()">
                    <i class="fa-solid fa-camera"></i> Activar Cámara
                </button>

                <div id="qr-reader" class="mx-auto" style="width: 100%; max-width: 500px; display: none;"></div>

                <div id="qr-reader-results" class="mt-4 text-start" style="max-width: 500px; margin: 0 auto;"></div>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="assets/js/escaner.js"></script>
</body>
</html>
