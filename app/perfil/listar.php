<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <?php if($_SESSION["current_user"]->can("perfil.add_perfil")): ?>
    <a type="button" class="btn btn-outline-primary" href="perfiles.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Perfil</th>
            <th class="no-sort">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $perfil) : ?>
            <tr>
                <td><?php echo htmlspecialchars($perfil["nombre"]); ?></td>
                <td class="text-center">
                    <?php if($_SESSION["current_user"]->can("perfil.view_perfil")): ?>
                    <a class="btn btn-outline-secondary" href="perfiles.php?accion=mostrar&pk=<?= urlencode($perfil['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("perfil.change_perfil")): ?>
                    <a class="btn btn-outline-secondary" href="perfiles.php?accion=actualizar&pk=<?= urlencode($perfil['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <?php endif; ?>
                    <?php if($_SESSION["current_user"]->can("perfil.delete_perfil")): ?>
                    <a class="btn btn-outline-danger" href="perfiles.php?accion=eliminar&pk=<?= urlencode($perfil['id']) ?>" onclick="return confirm('¿Eliminar este perfil?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
