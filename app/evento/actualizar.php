<?php
$object->get(getvar('pk'));
?>

<h2 class="text-secondary">
    <i class="fa-solid fa-pen-to-square me-2"></i>Actualizar Evento: <?php echo htmlspecialchars($object); ?>
</h2>

<div class="card">
    <div class="card-body">
        <form method="post"
              action="eventos.php?accion=actualizar"
              id="main-form"
              enctype="multipart/form-data"
              autocomplete="off">

            <?php include 'mainform.php'; ?>

            <input type="hidden" name="accion" value="update" />
            <input type="hidden" name="pk" value="<?php echo htmlspecialchars($object->pk ?? ''); ?>" />
            <hr />
            <button type="submit" class="btn btn-outline-primary">
                <i class="fa-regular fa-floppy-disk"></i>
                Guardar
            </button>
            <a href="eventos.php?accion=mostrar&pk=<?= urlencode($object->pk ?? '') ?>" class="btn btn-outline-secondary">
                <i class="fa-regular fa-circle-xmark"></i>
                Cancelar
            </a>
        </form>
    </div>
</div>
