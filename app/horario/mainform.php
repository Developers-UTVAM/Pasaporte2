<?php
$materiaModel = new Materia();
$materias = $materiaModel->getActivas();

$aulaModel = new Aula();
$aulas = $aulaModel->getActivas();

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
?>
<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="materia_id" class="form-label">Materia *</label>
        <select required class="form-select select2" id="materia_id" name="materia_id">
            <option value="">-- Seleccionar Materia --</option>
            <?php foreach ($materias as $m): ?>
                <option value="<?= htmlspecialchars($m['id']) ?>"
                    <?php if (isset($object) && (string)$object->materia_id === (string)$m['id']) { echo 'selected="selected"'; } ?>>
                    <?= htmlspecialchars(($m['clave'] ? '[' . $m['clave'] . '] ' : '') . $m['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

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
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="aula_id" class="form-label">Aula / Espacio</label>
        <select class="form-select select2" id="aula_id" name="aula_id">
            <option value="">-- Sin Aula Asignada --</option>
            <?php foreach ($aulas as $a): ?>
                <option value="<?= htmlspecialchars($a['id']) ?>"
                    <?php if (isset($object) && (string)$object->aula_id === (string)$a['id']) { echo 'selected="selected"'; } ?>>
                    <?= htmlspecialchars($a['codigo'] . ($a['edificio'] ? ' (' . $a['edificio'] . ')' : '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="grupo" name="grupo"
                placeholder="Grupo"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->grupo ?? 'A'); } else { echo 'A'; } ?>" />
            <label for="grupo">Grupo *</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="periodo" name="periodo"
                placeholder="Período Académico"
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
    <div class="col-sm-4 mb-3">
        <div class="form-check form-switch mt-2">
            <input type="hidden" name="activo" value="0" />
            <input type="checkbox" class="form-check-input" role="switch" id="activo" name="activo" value="1" <?php if(!isset($object) || $object->activo) { echo 'checked="checked"'; } ?> />
            <label for="activo">Horario Activo</label>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('#materia_id').select2({ width: '100%', placeholder: '-- Seleccionar Materia --' });
        $('#profesor_id').select2({ width: '100%', placeholder: '-- Seleccionar Profesor --' });
        $('#aula_id').select2({ width: '100%', placeholder: '-- Sin Aula Asignada --', allowClear: true });
    }
});
</script>
