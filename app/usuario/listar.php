<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">
    <a type="button" class="btn btn-outline-secondary" href="usuarios.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th></th>
            <th>Usuario</th>
            <th>Matricula</th>
            <th>Grupo</th>
            <th>Comunicación</th>
            <th>SU</th>
            <th class="no-sort">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $usuario) : ?>
            <tr>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" <?php echo $usuario["activo"] ? 'checked="checked"' : ''; ?> disabled="disabled" />
                    </div>
                </td>
                <td><?php echo htmlspecialchars(trim($usuario["nombre"] . " " . $usuario["apaterno"] . " " . $usuario["amaterno"])); ?></td>
                <td><?php echo htmlspecialchars($usuario["matricula"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($usuario["grupo"] ?? ""); ?></td>
                <td>
                    <a class="btn btn-outline-secondary" href="mailto:<?= $usuario['email']; ?>" target="_blank" >
                        <i class="fa-regular fa-envelope"></i>
                        E-Mail
                    </a>
                    <a class="btn btn-outline-secondary" href="tel:<?= $usuario['whatsapp']; ?>" target="_blank" >
                        <i class="fa-solid fa-phone"></i>
                        Teléfono
                    </a>
                    <a class="btn btn-outline-secondary" href="https://api.whatsapp.com/send?phone=52<?= $usuario['whatsapp']; ?>" target="_blank" >
                        <i class="fa-brands fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" <?php echo $usuario["superusuario"] ? 'checked="checked"' : ''; ?> disabled="disabled" />
                    </div>
                </td>
                <td class="text-center">
                    <a class="btn btn-outline-secondary" href="usuarios.php?accion=mostrar&pk=<?= urlencode($usuario['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        Mostrar
                    </a>
                    <a class="btn btn-outline-secondary" href="usuarios.php?accion=actualizar&pk=<?= urlencode($usuario['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Actualizar
                    </a>
                    <a class="btn btn-outline-danger" href="usuarios.php?accion=eliminar&pk=<?= urlencode($usuario['id']) ?>" onclick="return confirm('¿Eliminar este usuario?')">
                        <i class="fa-regular fa-trash-can"></i>
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
