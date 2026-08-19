<?php
$object->get(getvar('pk'));
?>

<h2 class="text-secondary">
    Actualizar Materia: <?php echo htmlspecialchars((string)$object); ?>
</h2>

<div class="card">
    <div class="card-body">
        <form method="post" 
              action="materias.php?accion=actualizar" 
              id="main-form" 
              autocomplete="off">

            <?php include 'mainform.php'; ?>

            <input type="hidden" name="accion" value="update" />

            <button type="submit" class="btn btn-outline-primary">
                <i class="fa-regular fa-floppy-disk"></i>
                Guardar
            </button>

            <a href="materias.php" class="btn btn-outline-secondary">
                <i class="fa-regular fa-circle-xmark"></i>
                Cancelar
            </a>
        </form>
    </div>
</div>
