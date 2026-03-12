<?php
// Administrar registros de usuarios para un evento específico
$eventoId = getvar('pk');
$object->get($eventoId);

$usuariosTable = new Table('usuario');
$usuarios = $usuariosTable->selectAll();

$registroTable = new Table('usuario_tiene_evento');
$registrados = $registroTable->selectAll('evento_id = ?', [$eventoId]);
$registradosIds = array_map(fn($r) => $r['usuario_id'], $registrados);
?>

<h2 class="text-secondary">Registro de usuarios para: <?php echo htmlspecialchars($object); ?></h2>

<div class="card"><div class="card-body">
    <form method="post" action="eventos.php?accion=guardar_registro&pk=<?= urlencode($eventoId) ?>">
        <p>Marca los usuarios que deseas registrar en este evento. Desmarca para quitar.</p>

        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) :
                        $checked = in_array($usuario['id'], $registradosIds, true);
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="usuarios[]" value="<?= htmlspecialchars($usuario['id']) ?>" <?= $checked ? 'checked' : '' ?> />
                            </td>
                            <td><?= htmlspecialchars($usuario['username']) ?></td>
                            <td><?= htmlspecialchars(trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apaterno'] ?? '') . ' ' . ($usuario['amaterno'] ?? ''))) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= htmlspecialchars($usuario['activo'] ? 'Sí' : 'No') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-outline-primary">
            <i class="fa-solid fa-floppy-disk"></i>
            Guardar registros
        </button>
        <a href="eventos.php?accion=mostrar&pk=<?= urlencode($eventoId) ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i>
            Volver al evento
        </a>
    </form>
</div></div>