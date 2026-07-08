<?php if (empty($eventos)): ?>
    <div class="glass-panel p-5 text-center shadow-lg">
        <i class="fa-regular fa-calendar-xmark mb-3" style="font-size: 3.5rem; color: rgba(255,255,255,0.4);"></i>
        <h4 style="color: var(--text-color); font-weight: bold;">No hay eventos disponibles</h4>
        <p class="text-light opacity-75 mb-0 fs-5">Por el momento no hay actividades abiertas para registro.</p>
    </div>
<?php else: ?>
    <div class="glass-panel p-4 shadow-lg">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 w-100 border-0">
        <thead>
            <tr>
                <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Nombre del Evento</th>
                <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Fecha / Hora</th>
                <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Lugar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tblRegistro = new Table('registro');
            foreach ($eventos as $ev):
                $yaRegistrado = $tblRegistro->select('usuario_id = ? AND evento_id = ?', [$_SESSION['current_user']->id, $ev['id']]);
            ?>
                <tr>
                    <td class="fw-bold fs-5" style="border: none; color: var(--primary);">
                        <?php echo htmlspecialchars($ev['nombre']); ?>
                    </td>
                    <td style="border: none;">
                        <div style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">
                            <i class="fa-regular fa-calendar me-1"></i>
                            <?php echo htmlspecialchars($ev['fecha_hora']); ?>
                        </div>
                    </td>
                    <td style="border: none;">
                        <span class="badge border py-2 px-3 fs-6" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo htmlspecialchars($ev['lugar']); ?>
                        </span>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <td colspan="3" class="pt-0 pb-4" style="border: none;">
                        <?php if (!$yaRegistrado): ?>
                        <form autocomplete="off" method="post" class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center m-0">
                            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($ev['id']); ?>">
                            <div class="form-floating flex-grow-1" style="max-width: 350px;">
                                <input type="text" name="equipo" id="equipo_<?php echo $ev['id']; ?>" class="form-control" placeholder="Nombre de equipo (Opcional)">
                                <label for="equipo_<?php echo $ev['id']; ?>" style="font-size: 0.9rem;">Nombre del equipo (Opcional)</label>
                            </div>
                            <button title="Registrarme" type="submit" class="btn btn-action-gradient fw-bold px-4 text-nowrap" style="height: 58px; border-radius: var(--radius-xl);">
                                <i class="fa-solid fa-check-to-slot me-2"></i> Registrarme
                            </button>
                        </form>
                        <?php else: ?>
                        <div class="d-inline-flex align-items-center px-4 py-2 rounded-pill shadow-sm" style="background: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.3);">
                            <span class="text-success fw-bold m-0 fs-6">
                                <i class="fa-solid fa-circle-check me-2"></i> ¡Ya estás registrado!
                            </span>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    </div>
<?php endif; ?>
