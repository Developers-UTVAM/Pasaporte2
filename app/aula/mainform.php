<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ""); } ?>" />

<div class="row">

    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="codigo" name="codigo"
                placeholder="Código"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->codigo ?? ""); } ?>" />
            <label for="codigo">Código <span class="text-danger">*</span></label>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="edificio" name="edificio"
                placeholder="Edificio"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->edificio ?? ""); } ?>" />
            <label for="edificio">Edificio / Ubicación</label>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <input type="number" min="1" max="9999" class="form-control" id="capacidad" name="capacidad"
                placeholder="Capacidad"
                value="<?php if (isset($object)) { echo htmlspecialchars($object->capacidad ?? ""); } ?>" />
            <label for="capacidad">Capacidad (personas)</label>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-floating mb-3">
            <select class="form-select" id="tipo" name="tipo" required>
                <?php
                $tipos = ["aula" => "Aula", "laboratorio" => "Laboratorio", "auditorio" => "Auditorio", "taller" => "Taller", "otro" => "Otro"];
                $tipo_actual = isset($object) ? ($object->tipo ?? "aula") : "aula";
                foreach ($tipos as $val => $label):
                ?>
                <option value="<?= htmlspecialchars($val) ?>" <?= $tipo_actual === $val ? "selected" : "" ?>>
                    <?= htmlspecialchars($label) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <label for="tipo">Tipo <span class="text-danger">*</span></label>
        </div>
    </div>

    <div class="col-sm-4 d-flex align-items-center">
        <div class="form-check form-switch ms-2">
            <input type="checkbox" class="form-check-input" role="switch"
                   id="activa" name="activa" value="1"
                   <?php if (isset($object) && $object->activa) { echo "checked"; } ?> />
            <label class="form-check-label" for="activa">Activa</label>
        </div>
    </div>

</div>
