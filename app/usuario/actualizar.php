<?php
$object->get(getvar('pk'));
?>
<h2 class="text-secondary">Actualizar Usuario: <?php echo htmlspecialchars($object); ?></h2>

<div class="card">
    <div class="card-body">
        <form method="post" action="usuarios.php?accion=actualizar" id="main-form" enctype="multipart/form-data"
            autocomplete="off">
            <?php include 'form_update.php'; ?>
            <input type="hidden" name="accion" value="update" />
            <button type="submit" class="btn btn-outline-primary">
                <i class="fa-regular fa-floppy-disk"></i>
                Guardar
            </button>
            <a href="usuarios.php" class="btn btn-outline-secondary">
                <i class="fa-regular fa-circle-xmark"></i>
                Cancelar
            </a>
        </form>
    </div>
</div>
