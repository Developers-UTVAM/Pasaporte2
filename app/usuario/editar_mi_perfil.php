<div class="card p-4 p-md-5 shadow-lg w-100" style="border-radius: 20px; background: rgba(15, 15, 20, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="card-body p-0">
        <form method="post" action="mi_perfil.php?accion=update" id="main-form" autocomplete="off">
            <div class="text-center mb-5">
                <div class="d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, color-mix(in oklab, var(--primary) 20%, transparent), rgba(0,0,0,0.5)); border: 1px solid color-mix(in oklab, var(--primary) 50%, transparent);">
                    <i class="fa-solid fa-user-gear" style="font-size: 2.2rem; color: var(--primary); filter: drop-shadow(0 0 10px color-mix(in oklab, var(--primary) 60%, transparent));"></i>
                </div>
                <h2 class="mb-1 fw-bold" style="color: #fff; letter-spacing: 0.5px;">Mi Perfil</h2>
                <p class="small m-0" style="color: rgba(255,255,255,0.6);">Actualiza tu información personal</p>
            </div>

            <?php include 'form_mi_perfil.php'; ?>
            <input type="hidden" name="accion" value="update" />
            <div class="mt-5 d-flex flex-column flex-sm-row justify-content-center gap-3">
                <button type="submit" class="btn btn-action-gradient py-3 fw-bold w-100">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Guardar cambios
                </button>
                <a href="index.php" class="btn btn-secondary py-3 fw-bold w-100 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-xmark me-2"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
