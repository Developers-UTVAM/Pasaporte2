<?php
$carreraModel = new Carrera();
$carreras = $carreraModel->getActivas();
?>
<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row">

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="clave" name="clave"
                placeholder="Clave"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->clave ?? ''); } ?>" />
            <label for="clave">Clave</label>
        </div>
    </div>

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="nombre" name="nombre"
                placeholder="Nombre"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
            <label for="nombre">Nombre</label>
        </div>
    </div>

</div>

<div class="row">
    <div class="col">
        <div class="form-floating mb-3">
            <input type="number" required min="0" class="form-control" id="asistencias" name="asistencias"
                placeholder="Asistencias"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->asistencias ?? '0'); } else { echo '0'; } ?>" />
            <label for="asistencias">Asistencias</label>
        </div>
    </div>

    <div class="col">
        <div class="form-floating mb-3">
            <input type="number" required min="0" class="form-control" id="horas_semana" name="horas_semana"
                placeholder="Horas Semana"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->horas_semana ?? '0'); } else { echo '0'; } ?>" />
            <label for="horas_semana">Horas Semana</label>
        </div>
    </div>

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="periodo" name="periodo"
                placeholder="Período"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->periodo ?? ''); } ?>" />
            <label for="periodo">Período</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8 mb-3">
        <label for="carrera_id" class="form-label">Carrera</label>
        <select class="form-select select2" id="carrera_id" name="carrera_id">
            <option value="">-- Sin Carrera --</option>
            <?php foreach ($carreras as $c): ?>
                <option value="<?= htmlspecialchars($c['id']) ?>"
                    <?php if (isset($object) && (string)$object->carrera_id === (string)$c['id']) { echo 'selected="selected"'; } ?>>
                    <?= htmlspecialchars(($c['clave'] ? '[' . $c['clave'] . '] ' : '') . $c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-sm-4 mb-3">
        <div class="form-check form-switch mt-4">
            <input type="hidden" name="activa" value="0" />
            <input type="checkbox" class="form-check-input" role="switch" id="activa" name="activa" value="1" <?php if(!isset($object) || $object->activa) { echo 'checked="checked"'; } ?> />
            <label for="activa">Activa</label>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('#carrera_id').select2({
            width: '100%',
            placeholder: '-- Sin Carrera --',
            allowClear: true
        });
    }
});
</script>
