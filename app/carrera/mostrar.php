<?php
$object->get(getvar('pk'));

// Obtener materias vinculadas a esta carrera
loadModels('materia');
$materiaModel = new Materia();
$materias = $materiaModel->getByCarrera($object->pk);
?>
<h2 class="text-secondary"><?php echo htmlspecialchars($object ?? ''); ?></h2>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if(currentUserCan("carrera.change_carrera")): ?>
    <a title="Actualizar" class="btn btn-outline-secondary" href="carreras.php?accion=actualizar&pk=<?= urlencode($object->pk) ?>">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("carrera.delete_carrera")): ?>
    <a title="Eliminar" class="btn btn-outline-danger" href="carreras.php?accion=eliminar&pk=<?= urlencode($object->pk) ?>"
        onclick="return confirm('¿Eliminar esta carrera?')">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("carrera.list_carrera")): ?>
    <a title="Ver todas" type="button" class="btn btn-outline-secondary" href="carreras.php?accion=listar">
        <i class="fa-solid fa-list-ul"></i>
    </a>
    <?php endif; ?>
    <?php if(currentUserCan("carrera.add_carrera")): ?>
    <a title="Nueva" type="button" class="btn btn-outline-secondary" href="carreras.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body">
    <fieldset disabled="disabled">
    <?php include 'mainform.php'; ?>
    </fieldset>
</div></div>

<h4 class="text-secondary mt-4">
    <i class="fa-solid fa-book"></i>
    Materias vinculadas
    <span class="badge bg-secondary ms-2"><?php echo count($materias); ?></span>
</h4>

<div class="card mt-2"><div class="card-body">
<?php if (empty($materias)): ?>
    <p class="text-muted mb-0">No hay materias vinculadas a esta carrera.</p>
<?php else: ?>
    <table id="data-list" class="table table-hover table-sm mb-0">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Créditos</th>
                <th>Cuatrimestre</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materias as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m['clave'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($m['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($m['creditos'] ?? '0'); ?></td>
                <td><?php echo htmlspecialchars($m['cuatrimestre'] ?? '—'); ?></td>
                <td>
                    <?php if($m['activa']): ?>
                        <span class="badge bg-success">Activa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactiva</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div></div>
