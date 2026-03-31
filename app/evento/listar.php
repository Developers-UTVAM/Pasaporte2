<div class="clearfix mb-3">
<div class="btn-group float-end" role="group" aria-label="Barra de Herramientas">

    <?php if (currentUserCan("evento.add_evento_masivo")): ?>
        <a type="button" class="btn btn-outline-primary" href="eventos.php?accion=carga-masiva">
            <i class="fa-solid fa-rectangle-list"></i>
            Carga Masiva
        </a>
    <?php endif; ?>

    <?php if(currentUserCan("usuario.add_usuario")): ?>
    <a type="button" class="btn btn-outline-primary" href="eventos.php?accion=crear">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </a>
    <?php endif; ?>


    <?php if(currentUserCan("evento.*")): ?>
    <?php if($accion !== 'listar_expirados'): ?>
    <a type="button" class="btn btn-outline-warning" href="eventos.php?accion=listar_expirados">
        <i class="fa-solid fa-clock"></i>
        Ver Expirados
    </a>
    <?php else: ?>
    <a type="button" class="btn btn-outline-primary" href="eventos.php?accion=listar">
        <i class="fa-solid fa-calendar"></i>
        Ver Activos
    </a>
    <?php endif; ?>
    <?php endif; ?>

</div>
</div>

<div class="card"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Fecha y hora</th>
            <th>Lugar</th>
            <th>Responsable Interno</th>
            <th>Responsable Externo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $eventos) : ?>
            <tr>
                <td>
                    <?= htmlspecialchars($eventos['nombre']) ?>
                    <?php
                    $now = new DateTime();
                    $fecha = new DateTime($eventos['fecha_hora']);
                    if ($fecha >= $now && $_SESSION['current_user']->can('usuario.autorregistrarse', false)): ?>
                        <br />
                        <form method="post" action="eventos.php" style="margin:0; display:inline-flex; gap: 0.25rem; align-items: center; justify-content: center; vertical-align: middle;">
                            <input type="hidden" name="accion" value="autoregistrar">
                            <input type="hidden" name="evento_id" value="<?= htmlspecialchars($eventos['id']) ?>">
                            <input type="text" name="equipo" class="form-control form-control-sm" placeholder="Nombre de equipo" style="width: 140px;">
                            <button title="Registrarme" type="submit" class="btn btn-primary btn-sm text-nowrap">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Registrarme
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars((new DateTime($eventos['fecha_hora']))->format('d/m/Y H:i')) ?></td>
                <td><?= htmlspecialchars($eventos['lugar']) ?></td>
                <td><?= htmlspecialchars($eventos['responsable_interno']) ?></td>
                <td><?= htmlspecialchars($eventos['responsable_externo']) ?></td>
                <td class="text-center">
                    <?php if(currentUserCan("evento.view_evento")): ?>
                    <a title="Mostrar" class="btn btn-outline-secondary" href="eventos.php?accion=mostrar&pk=<?= urlencode($eventos['id']) ?>">
                        <i class="fa-regular fa-eye"></i>
                        <!-- Mostrar -->
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("evento.change_evento")): ?>
                    <a title="Actualizar" class="btn btn-outline-secondary" href="eventos.php?accion=actualizar&pk=<?= urlencode($eventos['id']) ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <!-- Actualizar -->
                    </a>
                    <?php endif; ?>
                    <?php if(currentUserCan("evento.delete_evento")): ?>
                    <a title="Eliminar" class="btn btn-outline-danger" href="eventos.php?accion=eliminar&pk=<?= urlencode($eventos['id']) ?>" onclick="return confirm('¿Eliminar este evento?')">
                        <i class="fa-regular fa-trash-can"></i>
                        <!-- Eliminar -->
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
