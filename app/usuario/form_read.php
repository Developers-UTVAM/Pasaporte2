<?php
$evts = $object->query("
    select e.nombre, e.lugar, date_format(e.fecha_hora, '%d/%m/%Y %H:%i') as fecha_hora, e.responsable_interno as responsable,
	date_format(r.fecha_registro, '%d/%m/%Y %H:%i') as fecha_registro, r.equipo,
    date_format(a.fecha_entrada, '%d/%m/%Y %H:%i') as fecha_entrada, registrado_por
    from evento e
    inner join registro r on r.evento_id = e.id and r.usuario_id = ?
    left join asistencia a on a.evento_id = r.evento_id and a.usuario_id = r.usuario_id", [$object->pk]);
$usr_aux = new Usuario();
?><input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row">
    <div class="col-sm-6">
        <div class="form-floating mb-3">
            <input type="text" required="required" class="form-control" id="username" name="username" placeholder="Usuario" value="<?php if(isset($object)) { echo htmlspecialchars($object->username ?? ''); } ?>" />
            <label for="username">Usuario</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" required="required" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
            <label for="nombre">Nombre</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" required="required" class="form-control" id="apaterno" name="apaterno" placeholder="Apellido Paterno" value="<?php if (isset($object)) { echo htmlspecialchars($object->apaterno ?? ''); } ?>" />
            <label for="apaterno">Apellido Paterno</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="amaterno" name="amaterno" placeholder="Apellido Materno" value="<?php if (isset($object)) { echo htmlspecialchars($object->amaterno ?? ''); } ?>" />
            <label for="amaterno">Apellido Materno</label>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-floating mb-3">
            <input type="number" required="required" class="form-control" id="matricula" name="matricula" placeholder="Matricula" value="<?php if(isset($object)) { echo htmlspecialchars($object->matricula ?? ''); } ?>" />
            <label for="matricula">Matricula</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" required="required" class="form-control" id="grupo" name="grupo" placeholder="Grupo" value="<?php if (isset($object)) { echo htmlspecialchars($object->grupo ?? ''); } ?>" />
            <label for="grupo">Grupo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" required="required" class="form-control" id="email" name="email" placeholder="E-Mail" value="<?php if (isset($object)) { echo htmlspecialchars($object->email ?? ''); } ?>" />
            <label for="email">E-Mail</label>
        </div>
        <div class="form-floating mb-3">
            <input type="tel" required="required" class="form-control" id="whatsapp" name="whatsapp" placeholder="What's App" value="<?php if(isset($object)) { echo htmlspecialchars($object->whatsapp ?? ''); } ?>" />
            <label for="whatsapp">What's App</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Categoría" value="<?php if (isset($object)) { echo htmlspecialchars($object->categoria ?? ''); } ?>" />
            <label for="categoria">Categoría</label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="activo" name="activo" <?php echo isset($object) && $object->activo == 1 ? 'checked="checked"' : ''; ?> />
            <label class="form-check-label" for="activo">Activo</label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="superusuario" name="superusuario" <?php echo isset($object) && $object->superusuario == 1 ? 'checked="checked"' : ''; ?> />
            <label class="form-check-label" for="superusuario">Superusuario</label>
        </div>
    </div>
</div>

<h4 class="text-secondary mt-4">
    <i class="fa-regular fa-calendar-days"></i>
    Eventos en los que participa <?= $object; ?>
    <span class="badge bg-secondary ms-2"><?php echo count($evts); ?></span>
</h4>

<?php if (empty($evts)): ?>
    <p class="text-muted mb-0">No hay eventos registrados en este usuario.</p>
<?php else: ?>
    <table id="data-list" class="table table-hover table-sm mb-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Lugar</th>
                <th>Fecha</th>
                <th>Responsable</th>
                <th>Registro</th>
                <th>Asistencia</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evts as $evt):
            $usr_aux->get($evt['registrado_por']);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($evt['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($evt['lugar'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($evt['fecha_hora'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($evt['responsable'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($evt['fecha_registro'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($evt['fecha_entrada'] ?? 'Sin asistencia registrada'); ?></td>
                <td><?php echo htmlspecialchars($usr_aux && $evt['registrado_por'] ? $usr_aux : ''); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        let sortTable = () => {
            if(datatblDataList !== null) {
                datatblDataList.order([2, 'asc'], [0, 'asc']).draw();
            } else {
                setTimeout(sortTable, 100);
            }
        }
        sortTable();
    });
</script>

<div class="row">
    <div class="col-sm-6">
        <p class="h4">Permisos del Usuario:</p>
        <?php foreach($object->todosLosPermisos() as $perm): ?>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="<?php echo $perm["id"]; ?>" role="switch"
                id="perm-<?php echo $perm["id"]; ?>" name="permisos[]"
                <?php echo $object->can($perm["tipo"] . "." . $perm["codename"], false, false, true) ? 'checked="checked"' : ''; ?> />
            <label class="form-check-label" for="perm-<?php echo $perm["id"]; ?>">
                <?php echo $perm["nombre"] . ": " . $perm["tipo"] . "." . $perm["codename"]; ?>
            </label>
        </div>
        <?php endforeach;?>
    </div>
    <div class="col-sm-6">
        <p class="h4">Perfiles del Usuario:</p>
        <?php foreach($object->todosLosPerfiles() as $perf): ?>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" value="<?php echo $perf["id"]; ?>" role="switch"
                id="perf-<?php echo $perf["id"]; ?>" name="perfiles[]"
                <?php echo $object->hasProfile($perf["id"]) ? 'checked="checked"' : ''; ?> />
            <label class="form-check-label" for="perf-<?php echo $perf["id"]; ?>">
                <?php echo $perf["nombre"]; ?>
            </label>
        </div>
        <?php endforeach;?>
    </div>
</div>
