<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if($_SESSION["current_user"]->can("usuario.add_usuario")): ?>
    <a type="button" class="btn btn-outline-primary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Fecha y hora</th>
            <th>Lugar</th>
            <th>Responsable Interno</th>
            <th>Responsable Externo</th>
            <th>Costo interno</th>
            <th>Costo externo</th>
            <th>Registro</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $eventos) : ?>
            <tr>
                <td><?= htmlspecialchars($eventos['nombre']) ?></td>
                <td><?= htmlspecialchars($eventos['fecha_hora']) ?></td>
                <td><?= htmlspecialchars($eventos['lugar']) ?></td>
                <td><?= htmlspecialchars($eventos['responsable_interno']) ?></td>
                <td><?= htmlspecialchars($eventos['responsable_externo']) ?></td>
                <td><?= htmlspecialchars($eventos['costo_interno']) ?></td>
                <td><?= htmlspecialchars($eventos['costo_externo']) ?></td>
                <td><?= htmlspecialchars($eventos['requiere_registro']) ?></td>
                <td class="text-center">
                    <?php if($_SESSION["current_user"]->can("evento.view_evento")): ?>
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=mostrar&pk=<?= urlencode($eventos['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("evento.change_evento")): ?>
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($eventos['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("evento.delete_evento")): ?>
                    <a class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($eventos['id']) ?>" onclick="return confirm('¿Eliminar este evento?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
