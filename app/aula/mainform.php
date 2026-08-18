<?php
$tipos_aula = ['aula', 'laboratorio', 'auditorio', 'taller', 'otro'];
?>
<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row">

    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="codigo" name="codigo"
                placeholder="Código"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->codigo ?? ''); } ?>" />
            <label for="codigo">Código</label>
        </div>
    </div>

    <div class="col-sm-5">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="edificio" name="edificio"
                placeholder="Edificio"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->edificio ?? ''); } ?>" />
            <label for="edificio">Edificio</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-floating mb-3">
            <input type="number" min="0" class="form-control" id="capacidad" name="capacidad"
                placeholder="Capacidad"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->capacidad ?? ''); } ?>" />
            <label for="capacidad">Capacidad</label>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <select class="form-select" id="tipo" name="tipo" required>
                <?php foreach ($tipos_aula as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>"
                        <?php if(isset($object) && $object->tipo === $tipo) { echo 'selected'; } ?>>
                        <?= htmlspecialchars(ucfirst($tipo)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="tipo">Tipo</label>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-check form-switch">
            <input type="hidden" name="activa" value="0" />
            <input type="checkbox" class="form-check-input" role="switch" id="activa" name="activa" value="1" <?php if(isset($object) && $object->activa) { echo 'checked="checked"'; } ?> />
            <label for="activa">Activa</label>
        </div>
    </div>

</div>
