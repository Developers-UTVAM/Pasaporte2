<?php $materias = $object->getMaterias(); ?>
<h2 class="text-secondary"><i class="fa-solid fa-file-import"></i> Carga masiva de inscripciones</h2>
<div class="card"><div class="card-body">
    <p class="text-muted">El CSV debe contener una matrícula (<code>username</code>) o un ID de usuario por fila, en la primera columna. Puede incluir encabezado.</p>
    <form method="post" action="inscripciones.php?accion=carga_masiva" enctype="multipart/form-data">
        <input type="hidden" name="accion" value="carga_masiva">
        <div class="row g-3">
            <div class="col-md-5"><label class="form-label">Materia</label><select name="materia_id" class="form-select select2" required><option value="">Seleccionar...</option><?php foreach ($materias as $materia): ?><option value="<?= (int)$materia['id'] ?>"><?= htmlspecialchars($materia['clave'] . ' - ' . $materia['nombre']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-2"><label class="form-label">Grupo</label><input name="grupo" class="form-control" value="A" required></div>
            <div class="col-md-3"><label class="form-label">Periodo</label><input name="periodo" class="form-control" placeholder="2026-1" required></div>
            <div class="col-md-6"><label class="form-label">Archivo CSV</label><input type="file" name="archivo" class="form-control" accept=".csv,text/csv" required></div>
        </div>
        <div class="mt-3"><button class="btn btn-secondary"><i class="fa-solid fa-upload"></i> Procesar archivo</button> <a class="btn btn-outline-secondary" href="inscripciones.php">Cancelar</a></div>
    </form>
</div></div>
<script>document.addEventListener('DOMContentLoaded', function () { if ($.fn.select2) $('.select2').select2({ width: '100%' }); });</script>