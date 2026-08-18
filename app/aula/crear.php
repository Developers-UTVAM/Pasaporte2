<h2 class="text-secondary">Nueva Aula</h2>

<div class="card"><div class="card-body">
<form method="post" action="aulas.php?accion=crear" id="main-form" autocomplete="off">
    <?php include "mainform.php"; ?>
    <input type="hidden" name="accion" value="create" />
    <button type="submit" class="btn btn-outline-primary">
        <i class="fa-regular fa-floppy-disk"></i>
        Guardar
    </button>
    <a href="aulas.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i>
        Cancelar
    </a>
</form>
</div></div>
