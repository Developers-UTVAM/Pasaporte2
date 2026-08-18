<?php
$object->get(getvar('pk'));

// Obtener horarios asignados a esta aula
$tblHorario = new Table('horario');
$horarios = $tblHorario->query(
    "SELECT h.*, m.clave AS materia_clave, m.nombre AS materia_nombre,
            TRIM(CONCAT(u.nombre,' ',u.apaterno,' ',COALESCE(u.amaterno,''))) AS profesor_nombre
     FROM horario h
     JOIN materia m ON m.id = h.materia_id
     JOIN usuario u ON u.id = h.profesor_id
     WHERE h.aula_id = ? AND h.activo = 1
     ORDER BY FIELD(h.dia_semana,'lunes','martes','miercoles','jueves','viernes','sabado'), h.hora_inicio",
    [$object->pk]
);
?>
<h2 class="text-secondary"><?php echo htmlspecialchars($object ?? ''); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if(currentUserCan("aula.change_aula")): ?>
    <a title="Actualizar" class="btn btn-outline-secondary" href="aulas.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("aula.delete_aula")): ?>
    <a title="Eliminar" class="btn btn-outline-danger" href="aulas.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
        onclick="return confirm('¿Eliminar esta aula?')">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("aula.list_aula")): ?>
    <a title="Ver todas" type="button" class="btn btn-outline-secondary" href="aulas.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("aula.add_aula")): ?>
    <a title="Nueva" type="button" class="btn btn-outline-secondary" href="aulas.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include 'mainform.php'; ?>
    </fieldset>
</div></div>

<h4 class="text-secondary mt-4">
    <i class="fa-solid fa-clock"></i>
    Horarios asignados
    <span class="badge bg-secondary ms-2"><?php echo count($horarios); ?></span>
</h4>

<div class="card mt-2"><div class="card-body">
<?php if (empty($horarios)): ?>
    <p class="text-muted mb-0">No hay horarios asignados a esta aula.</p>
<?php else: ?>
    <table id="data-list" class="table table-hover table-sm mb-0">
        <thead>
            <tr>
                <th>Día</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Materia</th>
                <th>Profesor</th>
                <th>Grupo</th>
                <th>Periodo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horarios as $h): ?>
            <tr>
                <td><?php echo htmlspecialchars(ucfirst($h['dia_semana'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars($h['hora_inicio'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($h['hora_fin'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($h['materia_clave'] . ' - ' . $h['materia_nombre']); ?></td>
                <td><?php echo htmlspecialchars($h['profesor_nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($h['grupo'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($h['periodo'] ?? ''); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div></div>
