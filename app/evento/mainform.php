<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row">

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="nombre" name="nombre"
                placeholder="Nombre"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
            <label for="nombre">Nombre</label>
        </div>
    </div>

    <div class="col">
        <div class="form-floating mb-3">
            <input type="datetime-local" required class="form-control" id="fecha_hora" name="fecha_hora"
                placeholder="Fecha y hora"
                value="<?php if (isset($object) && $object->fecha_hora) {
                            echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($object->fecha_hora)));}?>" />
            <label for="fecha_hora">Fecha y hora</label>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="lugar" name="lugar" placeholder="Lugar" value="<?php if (isset($object)) {echo htmlspecialchars($object->lugar ?? '');} ?>" />
            <label for="lugar">Lugar</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input" role="switch" id="requiere_registro" name="requiere_registro" value="1" <?php if(isset($object) && $object->requiere_registro) { echo 'checked="checked"'; } ?>" />
            <label for="requiere_registro">Requiere registro</label>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input" role="switch" id="permitir_autorregistro" name="permitir_autorregistro"" value="1" <?php if (isset($object) && $object->permitir_autorregistro) { echo 'checked="checked"'; } ?>" />
            <label for="permitir_autorregistro">Permitir Auto-registro</label>
        </div>
    </div>
</div>



<div class="row">

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="responsable_interno" name="responsable_interno"
                placeholder="Responsable Interno"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_interno ?? ''); } ?>" />
            <label for="responsable_interno">Responsable Interno</label>
        </div>
    </div>

    <div class="col">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="responsable_externo" name="responsable_externo"
                placeholder="Responsable Externo"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_externo ?? ''); } ?>" />
            <label for="responsable_externo">Responsable Externo</label>
        </div>
    </div>

</div>
