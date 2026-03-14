<?php
$eventos    = $object->getTodosEventos();
$grupos     = $object->getGrupos();
$categorias = $object->getCategorias();

$filtro_evento    = intval(getvar('filtro_evento')    ?? 0);
$filtro_grupo     = getvar('filtro_grupo')    ?? '';
$filtro_categoria = getvar('filtro_categoria') ?? '';
$filtro_busqueda  = getvar('filtro_busqueda')  ?? '';

$hay_filtro = $filtro_evento > 0 || $filtro_grupo !== '' || $filtro_categoria !== '' || $filtro_busqueda !== '';

$registros = [];
if ($hay_filtro) {
    $registros = $object->listar($filtro_evento, $filtro_grupo, $filtro_categoria, $filtro_busqueda);
}
?>

<h2 class="text-secondary"><i class="fa-solid fa-pen-to-square"></i> Editar Registros</h2>

<div class="card mb-3">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-magnifying-glass"></i> Paso 1 — Buscar registros existentes
    </div>
    <div class="card-body">
        <form method="get" action="registroevento.php" class="row g-2 align-items-end">
            <input type="hidden" name="accion" value="editar" />

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold">Evento</label>
                <select name="filtro_evento" class="form-select">
                    <option value="">— Todos los eventos —</option>
                    <?php foreach ($eventos as $ev): ?>
                        <option value="<?= $ev['id'] ?>" <?= ($filtro_evento == $ev['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ev['nombre']) ?>
                            <?php if (!empty($ev['fecha_hora'])): ?>
                                (<?= date('d/m/Y', strtotime($ev['fecha_hora'])) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1 fw-semibold">Grupo</label>
                <select name="filtro_grupo" class="form-select">
                    <option value="">— Todos —</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= htmlspecialchars($g['grupo']) ?>"
                            <?= ($filtro_grupo === $g['grupo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['grupo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1 fw-semibold">Categoría</label>
                <select name="filtro_categoria" class="form-select">
                    <option value="">— Todas —</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['categoria']) ?>"
                            <?= ($filtro_categoria === $cat['categoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold">Matrícula o nombre</label>
                <input type="text" name="filtro_busqueda" class="form-control"
                       placeholder="Buscar..."
                       value="<?= htmlspecialchars($filtro_busqueda) ?>" />
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                <a href="registroevento.php?accion=editar" class="btn btn-outline-secondary w-100" title="Limpiar">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (!$hay_filtro): ?>
    <div class="alert alert-info">
        <i class="fa-solid fa-circle-info"></i>
        Usa los filtros para encontrar los registros que deseas modificar.
    </div>
    <a href="registroevento.php" class="btn btn-outline-secondary">
        <i class="fa-regular fa-circle-xmark"></i> Cancelar
    </a>

<?php elseif (empty($registros)): ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        No se encontraron registros con los filtros aplicados.
    </div>
    <a href="registroevento.php?accion=editar" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver a buscar
    </a>

<?php else: ?>

<form method="post" action="registroevento.php?accion=editar" id="form-editar">
    <input type="hidden" name="accion"           value="editar" />
    <input type="hidden" name="filtro_evento"    value="<?= $filtro_evento ?>" />
    <input type="hidden" name="filtro_grupo"     value="<?= htmlspecialchars($filtro_grupo) ?>" />
    <input type="hidden" name="filtro_categoria" value="<?= htmlspecialchars($filtro_categoria) ?>" />
    <input type="hidden" name="filtro_busqueda"  value="<?= htmlspecialchars($filtro_busqueda) ?>" />

    <div class="card mb-3">
        <div class="card-header fw-semibold">
            <i class="fa-solid fa-list-check"></i> Paso 2 — Selecciona los registros a modificar
        </div>
        <div class="card-body p-0">

            <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom bg-light flex-wrap">
                <span class="text-secondary">
                    <strong><?= count($registros) ?></strong> encontrado(s)
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-sel-todos">
                    <i class="fa-solid fa-check-double"></i> Todos
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-desel-todos">
                    <i class="fa-solid fa-xmark"></i> Ninguno
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:42px">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="chk-todos" class="form-check-input" role="switch" title="Marcar/desmarcar todos" />
                                </div>
                            </th>
                            <th>Evento actual</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Categoría</th>
                            <th>Fecha registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg): ?>
                            <tr class="fila-registro" style="cursor:pointer">
                                <td>
                                    <div class="form-check form-switch">
                                    <input type="checkbox"
                                           name="registros_sel[]"
                                           value="<?= $reg['evento_id'] ?>|<?= $reg['usuario_id'] ?>"
                                           class="form-check-input chk-registro"
                                           role="switch" />
                                </div>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($reg['evento_nombre']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($reg['username']) ?></td>
                                <td><?= htmlspecialchars(trim($reg['nombre'] . ' ' . $reg['apaterno'] . ' ' . $reg['amaterno'])) ?></td>
                                <td><?= htmlspecialchars($reg['grupo']) ?></td>
                                <td><?= htmlspecialchars($reg['categoria']) ?></td>
                                <td class="text-muted" style="font-size:.85em">
                                    <?= htmlspecialchars($reg['fecha_registro']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-secondary">
        <div class="card-header bg-secondary fw-semibold">
            <i class="fa-solid fa-sliders"></i> Paso 3 — ¿Qué hacer con los seleccionados?
        </div>
        <div class="card-body">

            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_accion"
                           id="accion-mover" value="mover" checked />
                    <label class="form-check-label fw-semibold" for="accion-mover">
                        <i class="fa-solid fa-right-left"></i> Mover a otro evento
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_accion"
                           id="accion-eliminar" value="eliminar" />
                    <label class="form-check-label text-danger fw-semibold" for="accion-eliminar">
                        <i class="fa-solid fa-ban"></i> Eliminar registros
                    </label>
                </div>
            </div>

            <div id="panel-mover">
                <label class="form-label fw-semibold">
                    Nuevo evento destino <span class="text-danger">*</span>
                </label>
                <select name="nuevo_evento_id" id="sel-nuevo-evento"
                        class="form-select" style="max-width:460px">
                    <option value="">— Selecciona el evento destino —</option>
                    <?php foreach ($eventos as $ev): ?>
                        <option value="<?= $ev['id'] ?>">
                            <?= htmlspecialchars($ev['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="panel-eliminar" class="d-none">
                <div class="alert alert-danger mb-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <strong>Atención:</strong> Los registros seleccionados se eliminarán permanentemente.
                </div>
            </div>

            <hr />

            <button type="submit" class="btn btn-secondary fw-semibold" id="btn-guardar">
                <i class="fa-regular fa-floppy-disk"></i>
                <span id="txt-boton">Guardar cambios</span>
            </button>
            <a href="registroevento.php" class="btn btn-outline-secondary ms-1">
                <i class="fa-regular fa-circle-xmark"></i> Cancelar
            </a>
        </div>
    </div>

</form>
<?php endif; ?>

<script src="assets/js/registroevento.js"></script>
