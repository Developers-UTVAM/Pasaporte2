<input type="hidden" id="accion" name="accion" value="login" />
<div class="row">
    <div class="col-sm-12">
        <div class="input-group-minimal">
            <span class="input-icon"><i class="fa-regular fa-user"></i></span>
            <div class="form-floating">
                <input type="text" required="required" class="form-control" id="username" name="username" placeholder=" " value="<?php echo $username ?? ''; ?>" />
                <label for="username">Usuario</label>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="input-group-minimal">
            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
            <div class="form-floating">
                <input type="password" required="required" class="form-control" id="password" name="password" placeholder=" " value="<?php echo $password ?? ''; ?>" />
                <label for="password">Contraseña</label>
            </div>
        </div>
    </div>
</div>


