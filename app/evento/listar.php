<?php
$data = $object->getAll();
?>

<div class="clearfix mb-3">
    <div class="btn-group float-end" role="group">
        <a class="btn btn-outline-secondary" href="eventos.php?accion=crear">
            <i class="fa-solid fa-plus"></i> Nuevo Evento
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table id="data-list" class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha y hora</th>
                    <th>Lugar</th>
                    <th>Resp. Interno</th>
                    <th>Resp. Externo</th>
                    <th class="text-center">Costo int.</th>
                    <th class="text-center">Costo ext.</th>
                    <th class="text-center">Registro</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $ev): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($ev['nombre']) ?></td>
                        <td class="text-nowrap">
                            <?php if (!empty($ev['fecha_hora'])): ?>
                                <i class="fa-regular fa-clock text-secondary me-1"></i>
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($ev['fecha_hora']))) ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ev['lugar'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($ev['responsable_interno'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($ev['responsable_externo'] ?: '—') ?></td>
                        <td class="text-center">
                            <?= $ev['costo_interno'] !== null ? '$' . number_format($ev['costo_interno'], 2) : '—' ?>
                        </td>
                        <td class="text-center">
                            <?= $ev['costo_externo'] !== null ? '$' . number_format($ev['costo_externo'], 2) : '—' ?>
                        </td>
                        <td class="text-center">
                            <?php if ($ev['requiere_registro']): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Sí</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-nowrap">
                            <a class="btn btn-sm btn-outline-secondary"
                               href="eventos.php?accion=mostrar&pk=<?= urlencode($ev['id']) ?>"
                               title="Ver detalle">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-secondary"
                               href="eventos.php?accion=actualizar&pk=<?= urlencode($ev['id']) ?>"
                               title="Editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-danger"
                               href="eventos.php?accion=eliminar&pk=<?= urlencode($ev['id']) ?>"
                               onclick="return confirm('¿Eliminar el evento «<?= addslashes(htmlspecialchars($ev['nombre'])) ?>»?')"
                               title="Eliminar">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if ($.fn.DataTable.isDataTable('#data-list')) {
        $('#data-list').DataTable().destroy();
    }
    $('#data-list').DataTable({
        responsive: true,
        order: [[1, 'desc']],
        columnDefs: [{ orderable: false, targets: -1 }],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            infoEmpty: "Sin eventos",
            zeroRecords: "No se encontraron eventos",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        }
    });
});
</script>
