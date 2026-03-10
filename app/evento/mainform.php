<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="row g-3 mb-3">

    <!-- Nombre -->
    <div class="col-12">
        <div class="form-floating">
            <input type="text" required class="form-control" id="nombre" name="nombre"
                placeholder="Nombre del evento"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
            <label for="nombre"><i class="fa-solid fa-calendar-days me-1 text-secondary"></i>Nombre del evento</label>
        </div>
    </div>

    <!-- Fecha/hora y Lugar -->
    <div class="col-md-6">
        <div class="form-floating">
            <input type="datetime-local" required class="form-control" id="fecha_hora" name="fecha_hora"
                placeholder="Fecha y hora"
                value="<?php if (isset($object->fecha_hora) && !empty($object->fecha_hora)) {
                    echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($object->fecha_hora)));} ?>" />
            <label for="fecha_hora"><i class="fa-regular fa-clock me-1 text-secondary"></i>Fecha y hora</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" class="form-control" id="lugar" name="lugar"
                placeholder="Lugar"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->lugar ?? ''); } ?>" />
            <label for="lugar"><i class="fa-solid fa-location-dot me-1 text-secondary"></i>Lugar</label>
        </div>
    </div>

</div>

<p class="text-secondary fw-semibold mb-2 mt-1">
    <i class="fa-solid fa-users me-1"></i>Responsables
</p>
<div class="row g-3 mb-3">

    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" class="form-control" id="responsable_interno" name="responsable_interno"
                placeholder="Responsable interno"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_interno ?? ''); } ?>" />
            <label for="responsable_interno"><i class="fa-solid fa-building-user me-1 text-secondary"></i>Responsable interno</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" class="form-control" id="responsable_externo" name="responsable_externo"
                placeholder="Responsable externo"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->responsable_externo ?? ''); } ?>" />
            <label for="responsable_externo"><i class="fa-solid fa-person-walking-arrow-right me-1 text-secondary"></i>Responsable externo</label>
        </div>
    </div>

</div>

<p class="text-secondary fw-semibold mb-2 mt-1">
    <i class="fa-solid fa-coins me-1"></i>Costos y registro
</p>
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label fw-semibold mb-1" for="costo_interno">
            <i class="fa-solid fa-tag me-1 text-secondary"></i>Costo interno
        </label>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01" min="0" class="form-control" id="costo_interno" name="costo_interno"
                placeholder="0.00"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->costo_interno ?? ''); } ?>" />
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold mb-1" for="costo_externo">
            <i class="fa-solid fa-tags me-1 text-secondary"></i>Costo externo
        </label>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01" min="0" class="form-control" id="costo_externo" name="costo_externo"
                placeholder="0.00"
                value="<?php if(isset($object)) { echo htmlspecialchars($object->costo_externo ?? ''); } ?>" />
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-end pb-1">
        <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" role="switch"
                id="requiere_registro" name="requiere_registro" value="1"
                <?php if(isset($object) && $object->requiere_registro) { echo 'checked'; } ?> />
            <label class="form-check-label fw-semibold" for="requiere_registro">
                Requiere registro
            </label>
        </div>
    </div>

</div>
