<?php
$object->get(getvar('pk'));
?>
<h2 class="text-secondary"><?php echo htmlspecialchars((string)$object); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if(currentUserCan("materia.change_materia")): ?>
    <a title="Actualizar" class="btn btn-outline-secondary" href="materias.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("materia.delete_materia")): ?>
    <a title="Eliminar" class="btn btn-outline-danger" href="materias.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
        onclick="return confirm('¿Eliminar esta materia?')">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("materia.view_materia")): ?>
    <a title="Ver todos" type="button" class="btn btn-outline-secondary" href="materias.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("materia.add_materia")): ?>
    <a title="Nuevo" type="button" class="btn btn-outline-secondary" href="materias.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include 'mainform.php'; ?>
    </fieldset>
</div></div>
