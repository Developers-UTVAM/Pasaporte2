<h2 class="text-secondary">
    <i class="fa-solid fa-calendar-plus me-2"></i>Nuevo Evento
</h2>

<div class="card">
    <div class="card-body">
        <form method="post" action="eventos.php?accion=crear" id="main-form" enctype="multipart/form-data" autocomplete="off">
            <?php include 'mainform.php'; ?>
            <input type="hidden" name="accion" value="create" />
            <hr />
            <button type="submit" class="btn btn-outline-primary">
                <i class="fa-regular fa-floppy-disk"></i>
                Guardar
            </button>
            <a href="eventos.php" class="btn btn-outline-secondary">
                <i class="fa-regular fa-circle-xmark"></i>
                Cancelar
            </a>
        </form>
    </div>
</div>
