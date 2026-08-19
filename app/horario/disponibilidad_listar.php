<?php
$data = $object->getTodasEnriquecidas();
?>
<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("horario.manage_disponibilidad")): ?>
    <a type="button" class="btn btn-outline-primary" href="disponibilidad.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo Bloque
    </a>
    <?php endif; ?>

</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Período</th>
            <th>Profesor / Docente</th>
            <th>Día</th>
            <th>Horario</th>
            <th>Tipo</th>
            <th>Notas</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $disp) : ?>
            <tr>
                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($disp['periodo']) ?></span></td>
                <td>
                    <i class="fa-solid fa-user-tie me-1 text-primary"></i>
                    <strong><?= htmlspecialchars($disp['profesor_nombre'] ?? '') ?></strong>
                </td>
                <td class="text-capitalize"><?= htmlspecialchars($disp['dia_semana'] ?? '') ?></td>
                <td>
                    <i class="fa-regular fa-clock me-1 text-primary"></i>
                    <?= htmlspecialchars(date('H:i', strtotime($disp['hora_inicio']))) ?> - <?= htmlspecialchars(date('H:i', strtotime($disp['hora_fin']))) ?>
                </td>
                <td>
                    <?php if($disp['tipo'] === 'disponible'): ?>
                        <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Disponible</span>
                    <?php else: ?>
                        <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>No Disponible</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($disp['notas'] ?? '—') ?></td>
                <td class="text-center text-nowrap">
                    <?php if(currentUserCan("horario.manage_disponibilidad")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="disponibilidad.php?accion=actualizar&pk=<?= urlencode($disp['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a title="Eliminar" class="btn btn-outline-danger" href="disponibilidad.php?accion=eliminar&pk=<?= urlencode($disp['id']) ?>" onclick="return confirm('¿Eliminar este bloque de disponibilidad?')">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        let sortTable = () => {
            if(datatblDataList !== null) {
                datatblDataList.order([0, 'desc'], [1, 'asc']).draw();
            } else {
                setTimeout(sortTable, 100);
            }
        }
        sortTable();
    });
</script>
