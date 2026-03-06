<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <a type="button" class="btn btn-outline-secondary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Fecha y hora</th>
            <th>Lugar</th>
            <th>Responsable Interno</th>
            <th>Responsable Externo</th>
            <th>Costo interno</th>
            <th>Costo externo</th>
            <th>Registro</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $permiso) : ?>
            <tr>
                <td><?= htmlspecialchars($permiso['nombre']) ?></td>
                <td><?= htmlspecialchars($permiso['fecha_hora']) ?></td>
                <td><?= htmlspecialchars($permiso['lugar']) ?></td>
                <td><?= htmlspecialchars($permiso['responsable_interno']) ?></td>
                <td><?= htmlspecialchars($permiso['responsable_externo']) ?></td>
                <td><?= htmlspecialchars($permiso['costo_interno']) ?></td>
                <td><?= htmlspecialchars($permiso['costo_externo']) ?></td>
                <td><?= htmlspecialchars($permiso['requiere_registro']) ?></td>
                <td class="text-center">
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=mostrar&pk=<?= urlencode($permiso['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($permiso['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <a class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($permiso['id']) ?>" onclick="return confirm('¿Eliminar este permiso?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
