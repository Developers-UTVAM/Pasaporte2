<h2 class="text-secondary">Nuevo Usuario</h2>

<div class="card"><div class="card-body">
<form method="post" action="usuarios.php?accion=crear" id="main-form" enctype="multipart/form-data" autocomplete="off">
    <?php include 'form_new.php'; ?>
    <input type="hidden" name="accion" value="create" />
    <button type="submit" class="btn btn-outline-primary">
        <i class="fa-regular fa-floppy-disk"></i>
        Guardar
    </button>
    <a href="usuarios.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i>
        Cancelar
    </a>
</form>
</div></div>
