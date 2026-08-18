<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("aula.add_aula")): ?>
    <a type="button" class="btn btn-outline-primary" href="aulas.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nueva
    </a>
    <?php endif; ?>

</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Código</th>
            <th>Edificio</th>
            <th>Capacidad</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $aula) : ?>
            <tr>
                <td><?= htmlspecialchars($aula['codigo']) ?></td>
                <td><?= htmlspecialchars($aula['edificio'] ?? '—') ?></td>
                <td><?= htmlspecialchars($aula['capacidad'] ?? '—') ?></td>
                <td><span class="badge bg-info text-dark"><?= htmlspecialchars(ucfirst($aula['tipo'])) ?></span></td>
                <td>
                    <?php if($aula['activa']): ?>
                        <span class="badge bg-success">Activa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if(currentUserCan("aula.view_aula")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="aulas.php?accion=mostrar&pk=<?= urlencode($aula['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("aula.change_aula")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="aulas.php?accion=actualizar&pk=<?= urlencode($aula['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("aula.delete_aula")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="aulas.php?accion=eliminar&pk=<?= urlencode($aula['id']) ?>" onclick="return confirm('¿Eliminar esta aula?')">
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
