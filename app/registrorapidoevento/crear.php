<?php
$eventos    = $object->getTodosEventos();
$grupos     = $object->getGrupos();
$categorias = $object->getCategorias();
 
if (isset($success) && $success !== null) {
    $sel_evento    = 0;
    $sel_grupo     = '';
    $sel_categoria = '';
    $busqueda      = '';
    $usuarios      = [];
    $disponibles   = [];
    $ya_inscritos  = [];
} else {
$sel_evento   = intval(getvar('evento_id')   ?? 0);
$sel_grupo    = getvar('grupo')     ?? '';
$sel_categoria = getvar('categoria') ?? '';
$busqueda     = getvar('busqueda')  ?? '';
 
$usuarios = [];
if ($sel_evento > 0) {
    $usuarios = $object->buscarUsuarios($busqueda, $sel_grupo, $sel_categoria);
}
 
$disponibles   = [];
$ya_inscritos  = [];
foreach ($usuarios as $usr) {
    if ($object->existeRegistro($sel_evento, $usr['id'])) {
        $ya_inscritos[] = $usr;
    } else {
        $disponibles[] = $usr;
    }
}
}
?>

<div class="card glass-panel p-4 shadow-lg border-0 mb-5">

        <form autocomplete="off" method="get" action="registrorapidoevento.php" class="row g-3 align-items-center mb-4" id="form-busqueda">
            <input type="hidden" name="accion" value="crear" />

            <div class="col-12 col-md-3">
                <div class="form-floating">
                    <select name="evento_id" id="evento_id" class="form-select" required>
                        <option value="">— Selecciona un evento —</option>
                        <?php foreach ($eventos as $ev): ?>
                            <option value="<?= $ev['id'] ?>" <?= ($sel_evento == $ev['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ev['nombre']) ?>
                                <?php if (!empty($ev['fecha_hora'])): ?>
                                    (<?= date('d/m/Y', strtotime($ev['fecha_hora'])) ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="evento_id">Evento <span class="text-danger">*</span></label>
                </div>
            </div>

            <div class="col-12 col-md-2">
                <div class="form-floating">
                    <select name="grupo" id="grupo" class="form-select">
                        <option value="">— Todos —</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?= htmlspecialchars($g['grupo']) ?>" <?= ($sel_grupo === $g['grupo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['grupo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="grupo">Grupo</label>
                </div>
            </div>

            <div class="col-12 col-md-2">
                <div class="form-floating">
                    <select name="categoria" id="categoria" class="form-select">
                        <option value="">— Todas —</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['categoria']) ?>" <?= ($sel_categoria === $cat['categoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="categoria">Categoría</label>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="form-floating">
                    <input type="text" name="busqueda" id="busqueda" class="form-control"
                           placeholder="Buscar..."
                           value="<?= htmlspecialchars($busqueda) ?>" />
                    <label for="busqueda">Matrícula o nombre</label>
                </div>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-action-gradient w-100" style="height: 58px; border-radius: var(--radius-xl);" title="Buscar">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                <a href="registrorapidoevento.php" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center" style="height: 58px; border-radius: var(--radius-xl);" title="Limpiar Búsqueda">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>

        <?php if ($sel_evento <= 0): ?>
            <div class="alert alert-dismissible fade show shadow-sm mx-auto" role="alert" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.3); color: var(--color-blue-400); border-radius: 16px;">
                <i class="fa-solid fa-circle-info me-2"></i>
                Selecciona un <strong>evento</strong> para ver y registrar usuarios.
            </div>
        <?php else: ?>

        <form autocomplete="off" method="post" action="registrorapidoevento.php" id="form-registro">
            <input type="hidden" name="accion"    value="crear" />
            <input type="hidden" name="evento_id" value="<?= $sel_evento ?>" />

            <div class="row g-3 mb-4 mt-2">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" name="equipo" id="equipo" class="form-control"
                               placeholder="Nombre del equipo"
                               value="<?= htmlspecialchars(getvar('equipo') ?? '') ?>" maxlength="50" />
                        <label for="equipo">Equipo <span class="text-muted fw-normal">(opcional)</span></label>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <span class="fw-bold fs-5 text-primary" style="text-shadow: 0 0 10px color-mix(in oklab, var(--primary) 40%, transparent);">
                    <i class="fa-solid fa-users me-2"></i>
                    <?= count($disponibles) ?> disponible(s)
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" id="btn-sel-todos">
                        <i class="fa-solid fa-check-double me-1"></i> Seleccionar todos
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-bold" id="btn-desel-todos">
                        <i class="fa-solid fa-xmark me-1"></i> Deseleccionar todos
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100 border-0" id="tabla-usuarios">
                    <thead>
                        <tr>
                            <th style="width:42px; border-bottom: 1px solid rgba(255,255,255,0.1) !important;">
                                <div class="form-check form-switch m-0">
                                    <input type="checkbox" id="chk-todos" class="form-check-input" role="switch" title="Marcar/desmarcar todos" />
                                </div>
                            </th>
                            <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Matrícula</th>
                            <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Nombre</th>
                            <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Grupo</th>
                            <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Categoría</th>
                            <th style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-light opacity-50 py-4 border-0">
                                    <i class="fa-solid fa-inbox fs-2 mb-2 d-block"></i>
                                    <?= $sel_evento > 0 ? 'Sin resultados con los filtros aplicados.' : 'Usa los filtros para encontrar usuarios.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($disponibles as $usr): ?>
                            <tr class="fila-disponible border-bottom border-light border-opacity-10">
                                <td class="border-0">
                                    <div class="form-check form-switch m-0">
                                    <input type="checkbox"
                                        name="usuario_ids[]"
                                        value="<?= $usr['id'] ?>"
                                        class="form-check-input chk-usuario"
                                        role="switch" />
                                </div>
                                </td>
                                <td class="border-0 text-light opacity-75 font-monospace"><i class="fa-solid fa-id-card me-1"></i> <?= htmlspecialchars($usr['username']) ?></td>
                                <td class="border-0 fw-bold text-white"><?= htmlspecialchars(trim($usr['nombre'] . ' ' . $usr['apaterno'] . ' ' . $usr['amaterno'])) ?></td>
                                <td class="border-0"><span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($usr['grupo']) ?></span></td>
                                <td class="border-0"><span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($usr['categoria']) ?></span></td>
                                <td class="border-0"><span class="text-success fw-bold" style="text-shadow: 0 0 10px rgba(25, 135, 84, 0.4);"><i class="fa-solid fa-circle-check"></i> Disponible</span></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($ya_inscritos as $usr): ?>
                            <tr class="fila-inscrita border-bottom border-light border-opacity-10 opacity-75">
                                <td class="border-0">
                                    <div class="form-check form-switch m-0">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               disabled
                                               role="switch"
                                               title="Ya registrado" />
                                    </div>
                                </td>
                                <td class="border-0 text-light opacity-75 font-monospace"><i class="fa-solid fa-id-card me-1"></i> <?= htmlspecialchars($usr['username']) ?></td>
                                <td class="border-0 fw-bold text-white"><?= htmlspecialchars(trim($usr['nombre'] . ' ' . $usr['apaterno'] . ' ' . $usr['amaterno'])) ?></td>
                                <td class="border-0"><span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($usr['grupo']) ?></span></td>
                                <td class="border-0"><span class="badge border" style="background: var(--glass-bg); color: var(--text-color); border-color: var(--glass-border) !important;"><?= htmlspecialchars($usr['categoria']) ?></span></td>
                                <td class="border-0"><span class="text-warning fw-bold"><i class="fa-solid fa-circle-exclamation"></i> Ya inscrito</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="glass-panel p-4 mt-4" id="panel-guardar" style="background: rgba(0,0,0,0.2);">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <div class="d-none d-md-block">
                        <div class="d-flex justify-content-center align-items-center shadow-sm" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, color-mix(in oklab, var(--secondary) 20%, transparent), rgba(0,0,0,0.5)); border: 1px solid color-mix(in oklab, var(--secondary) 50%, transparent);">
                            <i class="fa-solid fa-users-line text-secondary fs-3" style="filter: drop-shadow(0 0 10px color-mix(in oklab, var(--secondary) 60%, transparent));"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 text-center text-md-start">
                        <h5 class="mb-1 text-white fw-bold">Guardar Selección</h5>
                        <p class="mb-0 text-light opacity-75">Configura la selección de usuarios y presiona guardar.</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-3 w-100 w-md-auto">
                        <button type="submit" class="btn btn-action-gradient py-3 px-4 fw-bold shadow" id="btn-guardar">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Registros
                        </button>
                        <a href="registrorapidoevento.php" class="btn btn-secondary py-3 px-4 fw-bold shadow d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-xmark me-2"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>

        </form>

        <?php endif; ?>
</div>

<script src="assets/js/registroevento.js"></script>
