<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="form-floating mb-3">
    <input type="text" required class="form-control" id="nombre" name="nombre"
        placeholder="Nombre"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
    <label for="nombre">Nombre</label>
</div>

<div class="form-floating mb-3">
    <input type="datetime-local" required class="form-control" id="fecha_hora" name="fecha_hora"
        placeholder="Fecha y hora"
        value="<?php if (isset($object->fecha_hora) && !empty($object->fecha_hora)) {
                    echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($object->fecha_hora)));}?>" />
    <label for="fecha_hora">Fecha y hora</label>
</div>

<div class="form-floating mb-3">
    <input type="text" class="form-control" id="lugar" name="lugar"
        placeholder="Lugar"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->lugar ?? ''); } ?>" />
    <label for="lugar">Lugar</label>
</div>

<div class="form-floating mb-3">
    <input type="text" class="form-control" id="responsable_interno" name="responsable_interno"
        placeholder="Responsable Interno"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_interno ?? ''); } ?>" />
    <label for="responsable_interno">Responsable Interno</label>
</div>

<div class="form-floating mb-3">
    <input type="text" class="form-control" id="responsable_externo" name="responsable_externo"
        placeholder="Responsable Externo"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_externo ?? ''); } ?>" />
    <label for="responsable_externo">Responsable Externo</label>
</div>

<div class="form-floating mb-3">
    <input type="number" step="0.01" class="form-control" id="costo_interno" name="costo_interno"
        placeholder="Costo interno"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->costo_interno ?? ''); } ?>" />
    <label for="costo_interno">Costo interno</label>
</div>

<div class="form-floating mb-3">
    <input type="number" step="0.01" class="form-control" id="costo_externo" name="costo_externo"
        placeholder="Costo externo"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->costo_externo ?? ''); } ?>" />
    <label for="costo_externo">Costo externo</label>
</div>

<div class="form-floating mb-3">
    <input type="number" class="form-control" id="requiere_registro" name="requiere_registro"
        placeholder="Requiere registro"
        value="<?php if(isset($object)) { echo htmlspecialchars($object->requiere_registro ?? ''); } ?>" />
    <label for="requiere_registro">Requiere registro</label>
</div>
