<?php $data = $object->getAll(); ?>

<div class="clearfix mb-3">
    <div class="btn-group float-end">
        <a class="btn btn-outline-primary" href="administrador_eventos.php?accion=crear">
            <i class="fa-solid fa-plus"></i> Nueva actividad
        </a>
    </div>
</div>

<div class="card"><div class="card-body">
<table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Ícono</th>
            <th class="text-center">Requeridas</th>
            <th class="no-sort">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $cfg): ?>
    <tr>
        <td>
            <code><?= htmlspecialchars($cfg['tipo']) ?></code><br>
            <small class="text-muted">
                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $cfg['tipo']))) ?>
            </small>
        </td>

        <td>
            <i class="fa-solid <?= htmlspecialchars($cfg['icono']) ?> me-1"></i>
            <small><?= htmlspecialchars($cfg['icono']) ?></small>
        </td>

        <td class="text-center">
            <span class="badge bg-primary"><?= (int)$cfg['requerido'] ?></span>
        </td>

        <td class="text-center">
            <a title="Editar" class="btn btn-outline-secondary btn-sm"
               href="administrador_eventos.php?accion=actualizar&pk=<?= urlencode($cfg['id']) ?>">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <a title="Eliminar" class="btn btn-outline-danger btn-sm"
               href="administrador_eventos.php?accion=eliminar&pk=<?= urlencode($cfg['id']) ?>"
               onclick="return confirm('¿Eliminar este tipo de actividad?')">
                <i class="fa-regular fa-trash-can"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div></div>
