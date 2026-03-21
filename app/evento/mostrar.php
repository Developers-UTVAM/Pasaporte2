<?php
$object->get(getvar('pk'));
?>
<h2 class="text-secondary"><?php echo htmlspecialchars($object ?? ''); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if($_SESSION["current_user"]->can("evento.change_evento")): ?>
    <a class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
        Actualizar
    </a>
    <?php endif; ?>
    <?php if($_SESSION["current_user"]->can("evento.delete_evento")): ?>
    <a class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
        onclick="return confirm('¿Eliminar este evento?')">
        <i class="fa-regular fa-trash-can"></i>
        Eliminar
        <?php endif; ?>
    </a>
    <?php if($_SESSION["current_user"]->can("evento.list_evento")): ?>
    <a type="button" class="btn btn-outline-secondary" href="eventos.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
        Ver todos
    </a>
    <?php endif; ?>
    <?php if($_SESSION["current_user"]->can("evento.add_evento")): ?>
    <a type="button" class="btn btn-outline-secondary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include 'mainform.php'; ?>
    </fieldset>
</div></div>
