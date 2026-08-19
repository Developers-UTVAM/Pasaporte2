<?php
$materiaId = intval(getvar('materia_id') ?? 0);
$grupo = trim(getvar('grupo') ?? '');
$periodo = trim(getvar('periodo') ?? '');
$inscripciones = $object->listar($grupo, $periodo, $materiaId);
$materias = $object->getMaterias();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-secondary"><i class="fa-solid fa-user-graduate"></i> Inscripciones</h2>
    <div class="btn-group">
        <a class="btn btn-outline-secondary" href="inscripciones.php?accion=inscribir"><i class="fa-solid fa-user-plus"></i> Inscribir</a>
        <a class="btn btn-outline-secondary" href="inscripciones.php?accion=carga_masiva"><i class="fa-solid fa-file-import"></i> Carga masiva</a>
    </div>
</div>

<div class="card mb-3"><div class="card-body">
    <form method="get" action="inscripciones.php" class="row g-2 align-items-end">
        <input type="hidden" name="accion" value="listar">
        <div class="col-md-4"><label class="form-label">Materia</label><select name="materia_id" class="form-select select2"><option value="">Todas</option><?php foreach ($materias as $materia): ?><option value="<?= (int)$materia['id'] ?>" <?= $materiaId === (int)$materia['id'] ? 'selected' : '' ?>><?= htmlspecialchars($materia['clave'] . ' - ' . $materia['nombre']) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-2"><label class="form-label">Grupo</label><input name="grupo" class="form-control" value="<?= htmlspecialchars($grupo) ?>" maxlength="10"></div>
        <div class="col-md-3"><label class="form-label">Periodo</label><input name="periodo" class="form-control" value="<?= htmlspecialchars($periodo) ?>" placeholder="2026-1"></div>
        <div class="col-md-3 d-flex gap-2"><button class="btn btn-outline-secondary flex-grow-1"><i class="fa-solid fa-filter"></i> Filtrar</button><a class="btn btn-outline-secondary" href="inscripciones.php" title="Limpiar"><i class="fa-solid fa-xmark"></i></a></div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive"><table id="data-list" class="table table-hover table-sm mb-0">
    <thead><tr><th>Materia</th><th>Alumno</th><th>Matrícula</th><th>Grupo</th><th>Periodo</th><th>Fecha inscripción</th><th>Acciones</th></tr></thead>
    <tbody><?php foreach ($inscripciones as $inscripcion): ?><tr>
        <td><?= htmlspecialchars($inscripcion['materia_clave'] . ' - ' . $inscripcion['materia_nombre']) ?></td>
        <td><?= htmlspecialchars(trim($inscripcion['nombre'] . ' ' . $inscripcion['apaterno'] . ' ' . $inscripcion['amaterno'])) ?></td>
        <td><?= htmlspecialchars($inscripcion['username']) ?></td><td><?= htmlspecialchars($inscripcion['grupo']) ?></td><td><?= htmlspecialchars($inscripcion['periodo']) ?></td><td><?= htmlspecialchars($inscripcion['fecha_inscripcion']) ?></td>
        <td><a class="btn btn-sm btn-outline-danger" href="inscripciones.php?accion=baja&id=<?= (int)$inscripcion['id'] ?>" onclick="return confirm('¿Dar de baja esta inscripción?')"><i class="fa-solid fa-user-minus"></i> Dar de baja</a></td>
    </tr><?php endforeach; ?></tbody>
</table></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.DataTable && !$.fn.DataTable.isDataTable('#data-list')) {
        $('#data-list').DataTable({
            responsive: true,
            order: [[0, 'asc'], [1, 'asc']]
        });
    }

    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }
});
</script>