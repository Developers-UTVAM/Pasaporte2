<?php
$filtro_evento   = intval(getvar('evento_id') ?? 0);
$filtro_grupo    = getvar('grupo') ?? '';
$filtro_busqueda = getvar('busqueda') ?? '';

$eventos = $object->getTodosEventos();
$grupos  = $object->getGrupos();
$asistencias = $object->listar($filtro_evento, $filtro_grupo, $filtro_busqueda);

$can_delete = $_SESSION["current_user"]->can(["asistencia.delete_asistencia", "asistencia.*"]);
$can_delete = $_SESSION["current_user"]->can("asistencia.delete_asistencia") || $_SESSION["current_user"]->can("asistencia.asistencia.*");
?>
<div class="glass-panel mb-4 p-4">
    <div>
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <h2 class="mb-0 fw-bolder" style="color: var(--text-color);">
                <i class="fa-solid fa-filter me-2" style="color: var(--primary);"></i> Filtrar Historial
            </h2>
            <?php if ($_SESSION["current_user"]->can("asistencia.add_asistencia") || $_SESSION["current_user"]->can("asistencia.asistencia.*")): ?>
            <button type="button" class="btn btn-action-gradient w-100 w-lg-auto" data-bs-toggle="modal" data-bs-target="#modalRegistroAsistencia">
                <i class="fa-solid fa-qrcode me-2"></i> Registrar Asistencia
            </button>
            <?php endif; ?>
        </div>

        <form autocomplete="off" method="get" action="asistencia.php" class="row g-3 align-items-center">
            <input type="hidden" name="accion" value="listar" />

            <div class="col-12 col-md-3">
                <div class="form-floating">
                    <select name="evento_id" id="f_evento" class="form-select custom-select-glass">
                        <option value="0">Todos los eventos</option>
                        <?php foreach ($eventos as $ev): ?>
                            <option value="<?= $ev['id'] ?>" <?= ($filtro_evento == $ev['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ev['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="f_evento">Evento</label>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="form-floating">
                    <select name="grupo" id="f_grupo" class="form-select custom-select-glass">
                        <option value="">Todos</option>
                        <?php foreach ($grupos as $g): ?>
                            <?php if(!empty($g['grupo'])): ?>
                            <option value="<?= htmlspecialchars($g['grupo']) ?>" <?= ($filtro_grupo === $g['grupo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['grupo']) ?>
                            </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <label for="f_grupo">Grupo</label>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="form-floating">
                    <input type="text" name="busqueda" id="f_busqueda" class="form-control custom-input-glass"
                           placeholder="Matrícula o nombre..."
                           value="<?= htmlspecialchars($filtro_busqueda) ?>" />
                    <label for="f_busqueda">Buscar (Matrícula / Nombre)</label>
                </div>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-action-gradient w-100" style="height: 58px; border-radius: var(--radius-xl);" title="Buscar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="asistencia.php" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center" style="height: 58px; border-radius: var(--radius-xl);" title="Limpiar Filtros">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Historial de Asistencias -->
<div class="glass-panel mb-5 p-4">
    <div>
        <h2 class="mb-4 fw-bolder" style="color: var(--text-color);"><i class="fa-solid fa-list-check me-2" style="color: var(--primary);"></i> Registros Guardados</h2>
        <div class="table-responsive">
            <table id="data-list" class="table table-hover align-middle mb-0 w-100 border-0">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Estudiante</th>
                        <th>Grupo</th>
                        <th>Equipo</th>
                        <th>Fecha / Hora</th>
                        <th>Staff</th>
                        <?php if ($can_delete): ?>
                        <th class="text-center"><i class="fa-solid fa-gears"></i></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencias as $asistencia): ?>
                        <tr>
                            <td class="fw-bold" style="color: var(--primary);"><?= htmlspecialchars($asistencia['evento_nombre']) ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold" style="color: var(--text-color);"><?= htmlspecialchars(trim($asistencia['nombre'] . ' ' . $asistencia['apaterno'] . ' ' . $asistencia['amaterno'])) ?></span>
                                    <span style="color: rgba(255,255,255,0.6); font-family: monospace;"><i class="fa-solid fa-id-card me-1"></i><?= htmlspecialchars($asistencia['matricula']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($asistencia['grupo']) ?></span>
                            </td>
                            <td>
                                <span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($asistencia['equipo'] ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
                                    <i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($asistencia['fecha_entrada'])) ?><br>
                                    <i class="fa-regular fa-clock me-1"></i><?= date('h:i A', strtotime($asistencia['fecha_entrada'])) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge border" style="background: rgba(255,255,255,0.1); color: var(--text-color); border-color: var(--glass-border) !important;"><i class="fa-solid fa-user-tie me-1"></i><?= htmlspecialchars($asistencia['admin_nombre']) ?></span>
                            </td>
                            <?php if ($can_delete): ?>
                            <td class="text-center">
                                <a class="btn shadow-sm" style="background: color-mix(in oklab, var(--color-red-400) 15%, transparent); color: var(--color-red-400); border-radius: 12px; padding: 0.5rem 0.8rem;" href="asistencia.php?accion=eliminar&evento_id=<?= urlencode($asistencia['evento_id']) ?>&usuario_id=<?= urlencode($asistencia['usuario_id']) ?>" onclick="return confirm('¿Eliminar esta asistencia?')" title="Eliminar">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#data-list')) {
            $('#data-list').DataTable().destroy();
        }
        $('#data-list').DataTable({
            responsive: true,
            order: [[4, "desc"]], // Fecha de entrada
            language: {
                search: "",
                searchPlaceholder: "Filtrar en tabla..."
            }
        });
    }
});
</script>
