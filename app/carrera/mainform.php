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
    <div class="col-sm-3">
        <div class="form-check form-switch">
            <input type="hidden" name="activa" value="0" />
            <input type="checkbox" class="form-check-input" role="switch" id="activa" name="activa" value="1" <?php if(isset($object) && $object->activa) { echo 'checked="checked"'; } ?> />
            <label for="activa">Activa</label>
        </div>
    </div>
</div>
