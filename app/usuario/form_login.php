<input type="hidden" id="accion" name="accion" value="login" />
<div class="row">
    <div class="col-sm-12">
        <div class="form-floating mb-3">
            <input type="text" required="required" class="form-control" id="username" name="username" placeholder="Usuario" value="<?php echo $username ?? ''; ?>" />
            <label for="username">Usuario</label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-floating mb-3">
            <input type="password" required="required" class="form-control" id="password" name="password" placeholder="Contraaseña" value="<?php echo $password ?? ''; ?>" />
            <label for="password">Contraseña</label>
        </div>
    </div>
</div>
