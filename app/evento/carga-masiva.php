<h2>Carga masiva de eventos <small class="text-muted">(1 de 2)</small></h2>

<div class="alert alert-info" role="alert">
    Plantilla para carga masiva de eventos: <a href="assets/csv/plantilla_eventos.csv" download="plantilla_eventos.csv">plantilla_eventos.csv</a>
</div>

<form autocomplete="off" id="main-form" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="archivo-carga" class="form-label">Archivo de Carga Masiva:</label>
        <input type="file" class="form-control" id="archivo-carga" name="archivo-carga" placeholder="Archivo de Carga Masiva:" accept=".csv, text/csv" required="required" />
    </div>
    <input type="hidden" name="accion" value="add-many-step-2" />
    <button type="submit" class="btn btn-outline-primary">
        <i class="fa-solid fa-upload"></i>
        Cargar Eventos
    </button>
    <a href="eventos.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i>
        Cancelar
    </a>
</form>
