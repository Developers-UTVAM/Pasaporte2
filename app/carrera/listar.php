<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("carrera.add_carrera")): ?>
    <a type="button" class="btn btn-outline-primary" href="carreras.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nueva
    </a>
    <?php endif; ?>

</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Clave</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $carrera) : ?>
            <tr>
                <td><?= htmlspecialchars($carrera['clave']) ?></td>
                <td><?= htmlspecialchars($carrera['nombre']) ?></td>
                <td>
                    <?php if($carrera['activa']): ?>
                        <span class="badge bg-success">Activa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if(currentUserCan("carrera.view_carrera")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="carreras.php?accion=mostrar&pk=<?= urlencode($carrera['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("carrera.change_carrera")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="carreras.php?accion=actualizar&pk=<?= urlencode($carrera['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("carrera.delete_carrera")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="carreras.php?accion=eliminar&pk=<?= urlencode($carrera['id']) ?>" onclick="return confirm('¿Eliminar esta carrera?')">
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
                datatblDataList.order([0, 'asc']).draw();
            } else {
                setTimeout(sortTable, 100);
            }
        }
        sortTable();
    });
</script>
