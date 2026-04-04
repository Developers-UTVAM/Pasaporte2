<?php
$mis_eventos = $registro->listarPorUsuario($usuario_id);
?>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <h2>Eventos en los que estoy registrado</h2>
</div>

<?php if (empty($mis_eventos)): ?>
    <div class="alert alert-info" role="alert">
        Aún no estás registrado en ningún evento.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-list" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha y Hora</th>
                            <th>Lugar</th>
                            <th>Responsable Interno</th>
                            <th>Responsable Externo</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mis_eventos as $ev): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ev['nombre']); ?></td>
                                <td><?php echo htmlspecialchars((new DateTime($ev['fecha_hora']))->format('d/m/Y H:i')); ?></td>
                                <td><?php echo htmlspecialchars($ev['lugar'] ?? 'No especificado'); ?></td>
                                <td><?php echo htmlspecialchars($ev['responsable_interno'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($ev['responsable_externo'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars((new DateTime($ev['fecha_registro']))->format('d/m/Y H:i')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        let sortTable = () => {
            if(datatblDataList !== null) {
                datatblDataList.order([1, 'asc'], [0, 'asc']).draw();
            } else {
                setTimeout(sortTable, 100);
            }
        }
        sortTable();
    });
</script>
