<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="form-floating mb-3">
    <input type="text" required="required" class="form-control" id="nombre" name="nombre" placeholder="Nombre del evento" value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
    <label for="nombre">Nombre del evento</label>
</div>
<div class="form-floating mb-3">
    <input type="date" required="required" class="form-control" id="fecha" name="fecha" placeholder="Fecha del evento" value="<?php if(isset($object)) { echo htmlspecialchars($object->fecha ?? ''); } ?>" />
    <label for="fecha">Fecha del evento</label>
</div>
<div class="form-floating mb-3">
    <textarea required="required" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del evento"><?php if(isset($object)) { echo htmlspecialchars($object->descripcion ?? ''); } ?></textarea>
    <label for="descripcion">Descripción del evento</label>
</div>