<?php $materias = $object->getMaterias(); $alumnos = $object->getAlumnos(); ?>
<h2 class="text-secondary"><i class="fa-solid fa-user-plus"></i> Inscripción manual</h2>
<div class="card"><div class="card-body">
    <form method="post" action="inscripciones.php?accion=inscribir">
        <input type="hidden" name="accion" value="inscribir">
        <div class="row g-3 mb-3">
            <div class="col-md-5"><label class="form-label">Materia <span class="text-danger">*</span></label><select name="materia_id" class="form-select select2" required><option value="">Seleccionar...</option><?php foreach ($materias as $materia): ?><option value="<?= (int)$materia['id'] ?>"><?= htmlspecialchars($materia['clave'] . ' - ' . $materia['nombre']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-2"><label class="form-label">Grupo</label><input name="grupo" class="form-control" value="A" maxlength="10" required></div>
            <div class="col-md-3"><label class="form-label">Periodo</label><input name="periodo" class="form-control" placeholder="2026-1" required></div>
        </div>
        <label class="form-label">Alumnos <span class="text-danger">*</span></label>
        <select name="usuario_ids[]" class="form-select select2" multiple required><?php foreach ($alumnos as $alumno): ?><option value="<?= (int)$alumno['id'] ?>"><?= htmlspecialchars($alumno['username'] . ' - ' . trim($alumno['nombre'] . ' ' . $alumno['apaterno'] . ' ' . $alumno['amaterno'])) ?></option><?php endforeach; ?></select>
        <div class="mt-3"><button class="btn btn-secondary"><i class="fa-solid fa-floppy-disk"></i> Guardar inscripción</button> <a class="btn btn-outline-secondary" href="inscripciones.php">Cancelar</a></div>
    </form>
</div></div>
<script>document.addEventListener('DOMContentLoaded', function () { if ($.fn.select2) $('.select2').select2({ width: '100%', placeholder: 'Buscar alumnos...' }); });</script>