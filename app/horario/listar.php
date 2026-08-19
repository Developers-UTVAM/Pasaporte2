<?php
$data = $object->getTodosEnriquecidos();
?>
<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("horario.add_horario")): ?>
    <a type="button" class="btn btn-outline-primary" href="horarios.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo Horario
    </a>
    <?php endif; ?>

</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Período</th>
            <th>Materia</th>
            <th>Profesor</th>
            <th>Grupo</th>
            <th>Día</th>
            <th>Horario</th>
            <th>Aula</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $h) : ?>
            <tr>
                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($h['periodo']) ?></span></td>
                <td>
                    <strong><?= htmlspecialchars($h['materia_clave'] ?? '') ?></strong> - <?= htmlspecialchars($h['materia_nombre'] ?? '') ?>
                </td>
                <td><?= htmlspecialchars($h['profesor_nombre'] ?? '') ?></td>
                <td><span class="badge bg-dark text-light"><?= htmlspecialchars($h['grupo'] ?? 'A') ?></span></td>
                <td class="text-capitalize"><?= htmlspecialchars($h['dia_semana'] ?? '') ?></td>
                <td>
                    <i class="fa-regular fa-clock me-1 text-primary"></i>
                    <?= htmlspecialchars(date('H:i', strtotime($h['hora_inicio']))) ?> - <?= htmlspecialchars(date('H:i', strtotime($h['hora_fin']))) ?>
                </td>
                <td>
                    <?php if(!empty($h['aula_codigo'])): ?>
                        <span class="badge bg-secondary"><?= htmlspecialchars($h['aula_codigo']) ?></span>
                        <?php if(!empty($h['aula_edificio'])): ?>
                            <small class="text-light opacity-75">(<?= htmlspecialchars($h['aula_edificio']) ?>)</small>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-light opacity-75">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($h['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php if(currentUserCan("horario.view_horario")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="horarios.php?accion=mostrar&pk=<?= urlencode($h['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("horario.change_horario")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="horarios.php?accion=actualizar&pk=<?= urlencode($h['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("horario.delete_horario")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="horarios.php?accion=eliminar&pk=<?= urlencode($h['id']) ?>" onclick="return confirm('¿Eliminar este horario?')">
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
                datatblDataList.order([0, 'desc'], [3, 'asc']).draw();
            } else {
                setTimeout(sortTable, 100);
            }
        }
        sortTable();
    });
</script>
