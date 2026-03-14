<?php
include_once "app/usuario/model.php";
session_start();

include_once 'app/registro/controller.php';
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Registros a Eventos</h1>

        <?php
        $ok = getvar('ok') ?? '';
        if ($ok === 'masivo'):
            $n = intval(getvar('nuevos') ?? 0);
            $d = intval(getvar('dup')    ?? 0);
        ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-circle-check"></i>
                <strong><?= $n ?> registro(s) creado(s) correctamente.</strong>
                <?php if ($d > 0): ?>
                    &nbsp;(<?= $d ?> ya estaban inscritos y se omitieron.)
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($ok === 'mover'):
            $n  = intval(getvar('n')  ?? 0);
            $om = intval(getvar('om') ?? 0);
        ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-right-left"></i>
                <strong><?= $n ?> registro(s) movido(s) al nuevo evento.</strong>
                <?php if ($om > 0): ?>
                    &nbsp;(<?= $om ?> omitido(s): ya estaban en el evento destino.)
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($ok === 'elim'):
            $n = intval(getvar('n') ?? 0);
        ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fa-solid fa-ban"></i>
                <strong><?= $n ?> registro(s) eliminado(s).</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; ?>

        <?php
        if ($accion === 'crear') {
            include 'app/registro/crear.php';
        } elseif ($accion === 'editar') {
            include 'app/registro/editar.php';
        } else {
            include 'app/registro/listar.php';
        }
        ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
