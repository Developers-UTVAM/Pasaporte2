<?php $object->get(getvar('pk')); ?>
<h2 class="text-secondary">Editar: <?= htmlspecialchars($object) ?></h2>
<div class="card"><div class="card-body">
<form method="post" action="administrador_eventos.php?accion=actualizar" id="main-form" autocomplete="off">
    <?php include 'mainform.php'; ?>
    <input type="hidden" name="accion" value="update" />
    <button type="submit" class="btn btn-outline-primary"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
    <a href="administrador_eventos.php" class="btn btn-outline-secondary"><i class="fa-regular fa-circle-xmark"></i> Cancelar</a>
</form>
</div></div>
