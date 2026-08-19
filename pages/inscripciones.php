<?php
include_once __DIR__ . '/../init.php';
startAPI('usuario.*');
include_once 'app/inscripcion/controller.php';
?><!DOCTYPE html>
<html lang="es-MX"><head><?php include 'templates/head.php'; ?></head><body>
<?php include 'templates/header.php'; ?><main class="container">
    <?php if (getvar('ok') === 'inscribir'): ?><div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= (int)getvar('nuevos') ?> inscripción(es) guardada(s). <?php if ((int)getvar('dup') > 0): ?><?= (int)getvar('dup') ?> duplicada(s) omitida(s).<?php endif; ?></div><?php elseif (getvar('ok') === 'baja'): ?><div class="alert alert-warning"><i class="fa-solid fa-user-minus"></i> Inscripción dada de baja.</div><?php endif; ?>
    <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endforeach; ?>
    <?php if ($accion === 'inscribir'): include 'app/inscripcion/inscribir.php'; elseif ($accion === 'carga_masiva'): include 'app/inscripcion/carga_masiva.php'; else: include 'app/inscripcion/listar.php'; endif; ?>
</main><?php include 'templates/footer.php'; ?></body></html>