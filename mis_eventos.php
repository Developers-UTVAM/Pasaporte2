<?php
include_once __DIR__ . "/init.php";

startAPI("login", ["evento", "registroevento"]);

$accion = getvar('accion');
$registro = new Registro();
$errors = [];

$usuario_id = $_SESSION["current_user"]->id;

?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php'; ?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1 class="my-4">Mis Eventos</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php $mensaje = getvar('mensaje'); if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php
        if ($accion === 'listar' || $accion === null) {
            include 'app/mis_eventos/listar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
