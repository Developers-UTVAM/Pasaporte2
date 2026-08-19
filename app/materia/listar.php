<?php
$tblMateria = new Table('materia');
$data = $tblMateria->query(
    "SELECT m.*, c.clave AS carrera_clave, c.nombre AS carrera_nombre
     FROM materia m
     LEFT JOIN carrera c ON c.id = m.carrera_id
     ORDER BY m.clave ASC"
);
?>
<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("materia.add_materia")): ?>
    <a type="button" class="btn btn-outline-primary" href="materias.php?accion=crear">
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
            <th>Asistencias</th>
            <th>Hrs/Semana</th>
            <th>Período</th>
            <th>Carrera</th>
            <th>Estado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $materia) : ?>
            <tr>
                <td><?= htmlspecialchars($materia['clave']) ?></td>
                <td><?= htmlspecialchars($materia['nombre']) ?></td>
                <td><?= htmlspecialchars($materia['asistencias'] ?? '0') ?></td>
                <td><?= htmlspecialchars($materia['horas_semana'] ?? '0') ?></td>
                <td>
                    <?php if(!empty($materia['periodo'])): ?>
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($materia['periodo']) ?></span>
                    <?php else: ?>
                        <span class="text-light opacity-75">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($materia['carrera_nombre'])): ?>
                        <?= htmlspecialchars($materia['carrera_clave'] ? '[' . $materia['carrera_clave'] . '] ' . $materia['carrera_nombre'] : $materia['carrera_nombre']) ?>
                    <?php else: ?>
                        <span class="text-light opacity-75">General / Sin carrera</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($materia['activa']): ?>
                        <span class="badge bg-success">Activa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php if(currentUserCan("materia.view_materia")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="materias.php?accion=mostrar&pk=<?= urlencode($materia['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("materia.change_materia")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="materias.php?accion=actualizar&pk=<?= urlencode($materia['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("materia.delete_materia")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="materias.php?accion=eliminar&pk=<?= urlencode($materia['id']) ?>" onclick="return confirm('¿Eliminar esta materia?')">
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
