<?php
if ($accion === 'actualizar') {
    $object->get(getvar('pk'));
}

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->query(
    "SELECT DISTINCT u.id, u.nombre, u.apaterno, u.amaterno
     FROM usuario u
     INNER JOIN usuario_tiene_perfil up ON up.usuario_id = u.id
     INNER JOIN perfil p ON p.id = up.perfil_id
     WHERE p.nombre = 'profesor' AND u.activo = 1
     ORDER BY u.nombre, u.apaterno"
);
$dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
$esEdicion = ($accion === 'actualizar');
?>

<h2 class="text-secondary mb-3">
    <?= $esEdicion ? 'Actualizar Bloque de Disponibilidad' : 'Nuevo Bloque de Disponibilidad' ?>
</h2>

<div class="card"><div class="card-body">
<form method="post" action="disponibilidad.php?accion=<?= $esEdicion ? 'actualizar' : 'crear' ?>" id="main-form" autocomplete="off">
    <input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="profesor_id" class="form-label">Profesor / Docente *</label>
            <select required class="form-select select2" id="profesor_id" name="profesor_id">
                <option value="">-- Seleccionar Profesor --</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= htmlspecialchars($u['id']) ?>"
                        <?php if (isset($object) && (string)$object->profesor_id === (string)$u['id']) { echo 'selected="selected"'; } ?>>
                        <?= htmlspecialchars(trim($u['nombre'] . ' ' . $u['apaterno'] . ' ' . ($u['amaterno'] ?? ''))) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <div class="form-floating mb-3">
                <input type="text" required class="form-control" id="periodo" name="periodo"
                    placeholder="Período"
                    value="<?php if (isset($object)) { echo htmlspecialchars($object->periodo ?? '2026-1'); } else { echo '2026-1'; } ?>" />
                <label for="periodo">Período Académico *</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <select required class="form-select text-capitalize" id="dia_semana" name="dia_semana">
                    <?php foreach ($dias as $d): ?>
                        <option value="<?= $d ?>" <?php if (isset($object) && $object->dia_semana === $d) { echo 'selected="selected"'; } ?>>
                            <?= ucfirst($d) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="dia_semana">Día de la Semana *</label>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="time" required class="form-control" id="hora_inicio" name="hora_inicio"
                    placeholder="Hora Inicio"
                    value="<?php if (isset($object) && $object->hora_inicio) { echo htmlspecialchars(date('H:i', strtotime($object->hora_inicio))); } ?>" />
                <label for="hora_inicio">Hora Inicio *</label>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="time" required class="form-control" id="hora_fin" name="hora_fin"
                    placeholder="Hora Fin"
                    value="<?php if (isset($object) && $object->hora_fin) { echo htmlspecialchars(date('H:i', strtotime($object->hora_fin))); } ?>" />
                <label for="hora_fin">Hora Fin *</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <select required class="form-select" id="tipo" name="tipo">
                    <option value="disponible" <?php if (!isset($object) || $object->tipo === 'disponible') { echo 'selected="selected"'; } ?>>Disponible</option>
                    <option value="no_disponible" <?php if (isset($object) && $object->tipo === 'no_disponible') { echo 'selected="selected"'; } ?>>No Disponible</option>
                </select>
                <label for="tipo">Tipo de Bloque *</label>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="notas" name="notas"
                    placeholder="Notas / Observaciones"
                    value="<?php if (isset($object)) { echo htmlspecialchars($object->notas ?? ''); } ?>" />
                <label for="notas">Notas / Observaciones</label>
            </div>
        </div>
    </div>

    <input type="hidden" name="accion" value="<?= $esEdicion ? 'update' : 'create' ?>" />

    <button type="submit" class="btn btn-outline-primary">
        <i class="fa-regular fa-floppy-disk"></i>
        Guardar
    </button>
    <a href="disponibilidad.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i>
        Cancelar
    </a>
</form>
</div></div>
