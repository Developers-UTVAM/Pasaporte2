<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm custom-border border-secondary">
            <div class="card-body p-4 p-md-5">
                <h3 class="text-center mb-4 text-primary"><i class="fa-solid fa-key"></i> Cambiar Contraseña</h3>

                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>

                <form method="post" action="olvide_mi_contrasena.php">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pwd_actual" name="pwd_actual" required placeholder="Contraseña actual">
                        <label for="pwd_actual">Contraseña Actual</label>
                    </div>

                    <hr class="my-4" style="border-color: var(--glass-border);">

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pwd_nuevo" name="pwd_nuevo" required placeholder="Nueva contraseña">
                        <label for="pwd_nuevo">Nueva Contraseña</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="pwd_confirmar" name="pwd_confirmar" required placeholder="Confirmar nueva contraseña">
                        <label for="pwd_confirmar">Confirmar Nueva Contraseña</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 shadow">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar Contraseña
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary w-100 py-3 mt-3">
                        <i class="fa-solid fa-arrow-left"></i> Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
