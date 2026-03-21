<?php
$eventos    = $object->getTodosEventos();
$grupos     = $object->getGrupos();
$categorias = $object->getCategorias();

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
?>

<h2 class="text-secondary"><i class="fa-solid fa-user-plus"></i> Nuevo Registro</h2>

<div class="card">
    <div class="card-body">

        <form method="get" action="registroevento.php" class="row g-2 align-items-end mb-3" id="form-busqueda">
            <input type="hidden" name="accion" value="crear" />

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Evento <span class="text-danger">*</span></label>
                <select name="evento_id" class="form-select" required>
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
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1">Grupo</label>
                <select name="grupo" class="form-select">
                    <option value="">— Todos —</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= htmlspecialchars($g['grupo']) ?>" <?= ($sel_grupo === $g['grupo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['grupo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1">Categoría</label>
                <select name="categoria" class="form-select">
                    <option value="">— Todas —</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['categoria']) ?>" <?= ($sel_categoria === $cat['categoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Matrícula o nombre</label>
                <input type="text" name="busqueda" class="form-control"
                       placeholder="Buscar..."
                       value="<?= htmlspecialchars($busqueda) ?>" />
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                <a href="registroevento.php?accion=crear" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>

        <?php if ($sel_evento <= 0): ?>
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i>
                Selecciona un <strong>evento</strong> para ver y registrar usuarios.
            </div>
            <a href="registroevento.php" class="btn btn-outline-secondary">
                <i class="fa-regular fa-circle-xmark"></i> Cancelar
            </a>

        <?php else: ?>

        <form method="post" action="registroevento.php?accion=crear" id="form-registro">
            <input type="hidden" name="accion"    value="crear" />
            <input type="hidden" name="evento_id" value="<?= $sel_evento ?>" />

            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="fw-semibold text-secondary">
                    <i class="fa-solid fa-users"></i>
                    <?= count($disponibles) ?> disponible(s)
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-sel-todos">
                    <i class="fa-solid fa-check-double"></i> Seleccionar todos
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-desel-todos">
                    <i class="fa-solid fa-xmark"></i> Deseleccionar todos
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle" id="tabla-usuarios">
                    <thead class="table-light">
                        <tr>
                            <th style="width:42px">
                            <div class="form-check form-switch">
                                <input type="checkbox" id="chk-todos" class="form-check-input" role="switch" title="Marcar/desmarcar todos" />
                            </div>
                        </th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i class="fa-solid fa-inbox"></i>
                                    <?= $sel_evento > 0 ? 'Sin resultados con los filtros aplicados.' : 'Usa los filtros para encontrar usuarios.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($disponibles as $usr): ?>
                            <tr class="fila-disponible">
                                <td>
                                    <div class="form-check form-switch">
                                    <input type="checkbox" 
                                        name="usuario_ids[]" 
                                        value="<?= $usr['id'] ?>" 
                                        class="form-check-input chk-usuario" 
                                        role="switch" />
                                </div>
                                </td>
                                <td><?= htmlspecialchars($usr['username']) ?></td>
                                <td><?= htmlspecialchars(trim($usr['nombre'] . ' ' . $usr['apaterno'] . ' ' . $usr['amaterno'])) ?></td>
                                <td><?= htmlspecialchars($usr['grupo']) ?></td>
                                <td><?= htmlspecialchars($usr['categoria']) ?></td>
                                <td><span class="text-success"><i class="fa-solid fa-circle-check"></i> Disponible</span></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($ya_inscritos as $usr): ?>
                            <tr class="table-warning fila-inscrita">
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               disabled 
                                               role="switch" 
                                               title="Ya registrado" />
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($usr['username']) ?></td>
                                <td><?= htmlspecialchars(trim($usr['nombre'] . ' ' . $usr['apaterno'] . ' ' . $usr['amaterno'])) ?></td>
                                <td><?= htmlspecialchars($usr['grupo']) ?></td>
                                <td><?= htmlspecialchars($usr['categoria']) ?></td>
                                <td><span class="text-warning"><i class="fa-solid fa-circle-exclamation"></i> Ya inscrito</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card border-secondary mt-3" id="panel-guardar">
                <div class="card-body d-flex align-items-center gap-3 flex-wrap">
                    <div>
                        <i class="fa-solid fa-users-line text-secondary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fw-semibold">Configura la selección de usuarios y presiona guardar.</span>
                    </div>
                    <button type="submit" class="btn btn-secondary" id="btn-guardar">
                        <i class="fa-regular fa-floppy-disk"></i> Guardar Registros
                    </button>
                    <a href="registroevento.php" class="btn btn-outline-secondary">
                        <i class="fa-regular fa-circle-xmark"></i> Cancelar
                    </a>
                </div>
            </div>

        </form>

        <?php endif; ?>
    </div>
</div>

<script src="assets/js/registroevento.js"></script>
