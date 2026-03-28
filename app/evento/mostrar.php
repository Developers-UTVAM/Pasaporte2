<?php
$object->get(getvar('pk'));

$tblRegistro = new Table('registro');
$participantes = $tblRegistro->query(
    "SELECT u.id, TRIM(CONCAT(u.nombre,' ',u.apaterno,' ',COALESCE(u.amaterno,''))) AS nombre,
            u.matricula, u.grupo, u.email,
            IF(a.usuario_id IS NOT NULL, 1, 0) AS asistio,
            r.equipo
     FROM registro r
     JOIN usuario u ON u.id = r.usuario_id
     LEFT JOIN asistencia a ON a.evento_id = r.evento_id AND a.usuario_id = r.usuario_id
     WHERE r.evento_id = ?
     ORDER BY u.nombre, u.apaterno",
    [$object->pk]
);
?>
<h2 class="text-secondary"><?php echo htmlspecialchars($object ?? ''); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if($_SESSION["current_user"]->can("evento.change_evento")): ?>
    <a title="Actualizar" class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
        <!-- Actualizar -->
    </a>
    <?php endif; ?>
    <?php if($_SESSION["current_user"]->can("evento.delete_evento")): ?>
    <a title="Eliminar" class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
        onclick="return confirm('¿Eliminar este evento?')">
        <i class="fa-regular fa-trash-can"></i>
        <!-- Eliminar -->
        <?php endif; ?>
    </a>
    <?php if($_SESSION["current_user"]->can("evento.list_evento")): ?>
    <a title="Ver todos" type="button" class="btn btn-outline-secondary" href="eventos.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
        <!-- Ver todos -->
    </a>
    <?php endif; ?>
    <?php if($_SESSION["current_user"]->can("evento.add_evento")): ?>
    <a title="Nuevo" type="button" class="btn btn-outline-secondary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        <!-- Nuevo -->
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include 'mainform.php'; ?>
    </fieldset>
</div></div>

<h3 class="text-secondary mt-4">
    <i class="fa-solid fa-users"></i>
    Participantes
    <span class="badge bg-secondary ms-2"><?php echo count($participantes); ?></span>
</h3>

<div class="card mt-2"><div class="card-body">
<?php if (empty($participantes)): ?>
    <p class="text-muted mb-0">No hay usuarios registrados en este evento.</p>
<?php else: ?>
    <table class="table table-hover table-sm mb-0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Matrícula</th>
                <th>Grupo</th>
                <th>Email</th>
                <th>Equipo</th>
                <th class="text-center">Registrado</th>
                <th class="text-center">Asistió</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participantes as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($p['matricula'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($p['grupo'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($p['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($p['equipo'] ?? ''); ?></td>
                <td class="text-center">
                    <i class="fa-solid fa-circle-check text-success"></i>
                </td>
                <td class="text-center">
                    <?php if ($p['asistio'] == 1): ?>
                        <i class="fa-solid fa-circle-check text-success"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-circle-xmark text-danger"></i>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div></div>
