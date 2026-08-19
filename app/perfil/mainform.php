<input type="hidden" name="pk" value="<?php if (isset($object)) { echo htmlspecialchars($object->pk ?? ''); } ?>" />

<div class="form-floating mb-3">
    <input type="text" required="required" class="form-control" id="nombre" name="nombre" placeholder="Nombre del perfil" value="<?php if(isset($object)) { echo htmlspecialchars($object->nombre ?? ''); } ?>" />
    <label for="nombre">Nombre del perfil</label>
</div>

<h5 class="text-white fw-bold mt-4 mb-3"><i class="fa-solid fa-key me-2 text-primary"></i>Permisos del Perfil</h5>

<?php foreach($object->todosLosPermisos() as $perm): ?>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" value="<?php echo $perm["id"]; ?>" role="switch"
        id="perm-<?php echo $perm["id"]; ?>" name="permisos[]"
        <?php echo $object->can($perm["tipo"] . "." . $perm["codename"]) ? 'checked="checked"' : ''; ?> />
    <label class="form-check-label" for="perm-<?php echo $perm["id"]; ?>">
        <?php echo $perm["nombre"] . ": " . $perm["tipo"] . "." . $perm["codename"]; ?>
    </label>
</div>
<?php endforeach;?>
