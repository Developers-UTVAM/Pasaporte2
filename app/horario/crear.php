<h2 class="text-secondary">Nuevo Horario</h2>

<div class="card"><div class="card-body">
<form method="post" action="horarios.php?accion=crear" id="main-form" autocomplete="off">
    <?php include 'mainform.php'; ?>
    <input type="hidden" name="accion" value="create" />
    <button type="submit" class="btn btn-outline-primary">
        <i class="fa-regular fa-floppy-disk"></i>
        Guardar
    </button>
    <a href="horarios.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i>
        Cancelar
    </a>
</form>
</div></div>
