<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if(currentUserCan("usuario.restaturar_contraseña_otros")): ?>
    <a type="button" class="btn btn-outline-secondary" href="usuarios.php?accion=restaurar-pwd">
        <i class="fa-solid fa-key"></i>
        Restaurar Contraseña
    </a>
    <?php endif; ?>

    <?php if (currentUserCan("usuario.add_usuario_masivo")): ?>
        <a type="button" class="btn btn-outline-primary" href="usuarios.php?accion=carga-masiva">
            <i class="fa-solid fa-rectangle-list"></i>
            Carga Masiva
        </a>
    <?php endif; ?>
    <?php if(currentUserCan("usuario.add_usuario")): ?>
    <a type="button" class="btn btn-outline-primary" href="usuarios.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>
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
                <td class="<?php echo $usuario["superusuario"] ? 'text-info' : ''; ?>">
                    <?php echo htmlspecialchars(trim($usuario["nombre"] . " " . $usuario["apaterno"] . " " . $usuario["amaterno"])); ?>
                </td>
                <td><?php echo htmlspecialchars($usuario["matricula"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($usuario["grupo"] ?? ""); ?></td>
                <td>
                    <a title="Enviar E-Mail" class="btn btn-outline-secondary" href="mailto:<?= htmlspecialchars($usuario['email'] ?? ''); ?>" target="_blank" >
                        <i class="fa-regular fa-envelope"></i>
                        <!-- E-Mail -->
                    </a>
                    <?php if($usuario['whatsapp']): ?>
                    <a title="Llamar por Teléfono" class="btn btn-outline-secondary" href="tel:<?= htmlspecialchars($usuario['whatsapp']); ?>" target="_blank" >
                        <i class="fa-solid fa-phone"></i>
                        <!-- Teléfono -->
                    </a>
                    <a title="Enviar WhatsApp" class="btn btn-outline-secondary" href="https://api.whatsapp.com/send?phone=52<?= htmlspecialchars($usuario['whatsapp']); ?>" target="_blank" >
                        <i class="fa-brands fa-whatsapp"></i>
                        <!-- WhatsApp -->
                    </a>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if(currentUserCan("usuario.view_usuario")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="usuarios.php?accion=mostrar&pk=<?= urlencode($usuario['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        <!-- Mostrar -->
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("usuario.change_usuario")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="usuarios.php?accion=actualizar&pk=<?= urlencode($usuario['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <!-- Actualizar -->
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("usuario.delete_usuario")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="usuarios.php?accion=eliminar&pk=<?= urlencode($usuario['id']) ?>" onclick="return confirm('¿Eliminar este usuario?')">
                        <i class="fa-regular fa-trash-can"></i>
                        <!-- Eliminar -->
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
