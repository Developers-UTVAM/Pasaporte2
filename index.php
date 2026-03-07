<?php

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: app/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1 class="text-center my-4">Pasaporte TICs 2026</h1>

        <!-- Referencia al módulo de QR -->
        <?php include 'templates/qr_usuario.php'; ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
