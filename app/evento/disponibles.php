<?php
// Mostrar solo eventos futuros (fecha >= hoy)
$hoy = date('Y-m-d');
$all = $object->getAll();
$data = array_filter($all, function ($evento) use ($hoy) {
    return isset($evento['fecha']) && $evento['fecha'] >= $hoy;
});
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <a type="button" class="btn btn-outline-secondary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <a type="button" class="btn btn-outline-secondary" href="eventos.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
        Ver todos
    </a>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th class="no-sort">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $evento) : ?>
            <tr>
                <td><?= htmlspecialchars($evento['nombre']) ?></td>
                <td><?= htmlspecialchars($evento['fecha']) ?></td>
                <td><?= htmlspecialchars($evento['descripcion']) ?></td>
                <td class="text-center">
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=mostrar&pk=<?= urlencode($evento['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <a class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($evento['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <a class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($evento['id']) ?>" onclick="return confirm('¿Eliminar este evento?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>