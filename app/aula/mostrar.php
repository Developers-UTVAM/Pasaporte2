<?php
$object->get(getvar("pk"));

// Cargar horarios asignados a este aula (si la tabla ya existe)
$horarios_aula = [];
try {
    $tblHorario = new Table("horario");
    $horarios_aula = $tblHorario->query(
        "SELECT h.id, h.grupo, h.dia_semana, h.hora_inicio, h.hora_fin, h.periodo,
                m.nombre AS materia_nombre, m.clave AS materia_clave,
                TRIM(CONCAT(u.nombre, ' ', u.apaterno, ' ', COALESCE(u.amaterno, ''))) AS profesor_nombre
         FROM horario h
         JOIN materia m ON m.id = h.materia_id
         JOIN usuario u ON u.id = h.profesor_id
         WHERE h.aula_id = ?
         ORDER BY FIELD(h.dia_semana,'lunes','martes','miercoles','jueves','viernes','sabado'), h.hora_inicio",
        [$object->pk]
    );
} catch (Exception $e) {
    // La tabla horario aún no existe; se muestra vacío sin error
}
?>

<h2 class="text-secondary"><?php echo htmlspecialchars($object->codigo ?? ""); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group">
    <?php if (currentUserCan("aula.change_aula")): ?>
    <a title="Editar" class="btn btn-outline-secondary"
       href="aulas.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <?php endif; ?>
    <?php if (currentUserCan("aula.delete_aula")): ?>
    <a title="Eliminar" class="btn btn-outline-danger"
       href="aulas.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
       onclick="return confirm('¿Eliminar el aula <?= htmlspecialchars(addslashes($object->codigo ?? "")) ?>?')">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <?php endif; ?>
    <a title="Ver todas" class="btn btn-outline-secondary" href="aulas.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
    </a>
    <?php if (currentUserCan("aula.add_aula")): ?>
    <a title="Nueva" class="btn btn-outline-secondary" href="aulas.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
    </a>
    <?php endif; ?>
</div>
</div>

<!-- Datos del aula -->
<div class="card mb-4"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include "mainform.php"; ?>
    </fieldset>
</div></div>

<!-- Horarios asignados -->
<h4 class="text-secondary mt-4">
    <i class="fa-solid fa-clock"></i>
    Horarios asignados
    <span class="badge bg-secondary ms-2"><?= count($horarios_aula) ?></span>
</h4>

<div class="card mt-2"><div class="card-body">
<?php if (empty($horarios_aula)): ?>
    <p class="text-muted mb-0">No hay horarios asignados a esta aula.</p>
<?php else: ?>
    <table id="data-list" class="table table-hover table-sm mb-0">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Profesor</th>
                <th>Grupo</th>
                <th>Día</th>
                <th>Horario</th>
                <th>Período</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horarios_aula as $h): ?>
            <tr>
                <td>
                    <span class="badge bg-primary me-1"><?= htmlspecialchars($h["materia_clave"]) ?></span>
                    <?= htmlspecialchars($h["materia_nombre"]) ?>
                </td>
                <td><?= htmlspecialchars($h["profesor_nombre"]) ?></td>
                <td><?= htmlspecialchars($h["grupo"]) ?></td>
                <td><?= ucfirst(htmlspecialchars($h["dia_semana"])) ?></td>
                <td><?= htmlspecialchars(substr($h["hora_inicio"], 0, 5)) ?> – <?= htmlspecialchars(substr($h["hora_fin"], 0, 5)) ?></td>
                <td><?= htmlspecialchars($h["periodo"]) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div></div>
