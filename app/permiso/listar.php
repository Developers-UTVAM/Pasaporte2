<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if($_SESSION["current_user"]->can("permiso.add_permiso")): ?>
    <a type="button" class="btn btn-outline-primary" href="permisos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Codename</th>
            <th>Nombre</th>
            <th class="no-sort">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $permiso) : ?>
            <tr>
                <td><?= htmlspecialchars($permiso['tipo']) ?></td>
                <td><?= htmlspecialchars($permiso['codename']) ?></td>
                <td><?= htmlspecialchars($permiso['nombre']) ?></td>
                <td class="text-center">
                    <?php if($_SESSION["current_user"]->can("permiso.view_permiso")): ?>
                    <a class="btn btn-outline-secondary" href="permisos.php?accion=mostrar&pk=<?= urlencode($permiso['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("permiso.change_permiso")): ?>
                    <a class="btn btn-outline-secondary" href="permisos.php?accion=actualizar&pk=<?= urlencode($permiso['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("permiso.delete_permiso")): ?>
                    <a class="btn btn-outline-danger" href="permisos.php?accion=eliminar&pk=<?= urlencode($permiso['id']) ?>" onclick="return confirm('¿Eliminar este permiso?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
