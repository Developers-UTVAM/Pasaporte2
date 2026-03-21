<?php
$filtro_evento    = intval(getvar('evento_id')  ?? 0);
$filtro_grupo     = getvar('grupo')     ?? '';
$filtro_categoria = getvar('categoria') ?? '';
$filtro_busqueda  = getvar('busqueda')  ?? '';

$registros  = $object->listar($filtro_evento, $filtro_grupo, $filtro_categoria, $filtro_busqueda);
$eventos    = $object->getTodosEventos();
$grupos     = $object->getGrupos();
$categorias = $object->getCategorias();
?>

<div class="clearfix mb-3">
    <div class="btn-group float-end" role="group">
        <a class="btn btn-outline-secondary" href="registroevento.php?accion=crear">
            <i class="fa-solid fa-plus"></i> Nuevo Registro
        </a>
        <a class="btn btn-outline-secondary" href="registroevento.php?accion=editar">
            <i class="fa-solid fa-pen-to-square"></i> Editar Registros
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-semibold">
        <i class="fa-solid fa-filter"></i> Filtrar Registros
    </div>
    <div class="card-body">
        <form method="get" action="registroevento.php" class="row g-2 align-items-end">
            <input type="hidden" name="accion" value="listar" />

            <div class="col-md-3">
                <label class="form-label mb-1">Evento</label>
                <select name="evento_id" class="form-select form-select-sm">
                    <option value="">— Todos los eventos —</option>
                    <?php foreach ($eventos as $ev): ?>
                        <option value="<?= $ev['id'] ?>" <?= ($filtro_evento == $ev['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ev['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label mb-1">Grupo</label>
                <select name="grupo" class="form-select form-select-sm">
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
                <label class="form-label mb-1">Categoría</label>
                <select name="categoria" class="form-select form-select-sm">
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
                <label class="form-label mb-1">Matrícula / Nombre</label>
                <input type="text" name="busqueda" class="form-control form-control-sm"
                       placeholder="Buscar..."
                       value="<?= htmlspecialchars($filtro_busqueda) ?>" />
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>
                <a href="registroevento.php" class="btn btn-outline-secondary btn-sm w-100" title="Limpiar">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="data-list" class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Evento</th>
                    <th>Fecha Evento</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>Grupo</th>
                    <th>Categoría</th>
                    <th>Fecha Registro</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                    <tr>
                        <td><?= htmlspecialchars($reg['evento_nombre']) ?></td>
                        <td><?= htmlspecialchars($reg['evento_fecha']) ?></td>
                        <td><?= htmlspecialchars($reg['username']) ?></td>
                        <td><?= htmlspecialchars(trim($reg['nombre'] . ' ' . $reg['apaterno'] . ' ' . $reg['amaterno'])) ?></td>
                        <td><?= htmlspecialchars($reg['grupo']) ?></td>
                        <td><?= htmlspecialchars($reg['categoria']) ?></td>
                        <td><?= htmlspecialchars($reg['fecha_registro']) ?></td>
                        <td class="text-center text-nowrap">
                            <a class="btn btn-sm btn-outline-danger"
                               href="registroevento.php?accion=eliminar&evento_id=<?= urlencode($reg['evento_id']) ?>&usuario_id=<?= urlencode($reg['usuario_id']) ?>"
                               onclick="return confirm('¿Eliminar este registro?')">
                                <i class="fa-solid fa-ban"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="assets/js/registroevento.js"></script>
