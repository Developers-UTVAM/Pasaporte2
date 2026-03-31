<?php
?>

<?php if (empty($eventos)): ?>
    <p>No hay eventos pendientes disponibles para registro.</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <!--<th></th>-->
            </tr>
        </thead>
        <tbody>
            <?php 
            $tblRegistro = new Table('registro');
            foreach ($eventos as $ev): 
                $yaRegistrado = $tblRegistro->select('usuario_id = ? AND evento_id = ?', [$_SESSION['current_user']->id, $ev['id']]);
            ?>
                <tr>
                    <td style="border: none;">
                        <?php echo htmlspecialchars($ev['nombre']); ?>
                    </td>
                    <td style="border: none;">
                        <?php echo htmlspecialchars($ev['fecha_hora']); ?>
                    </td>
                    <td style="border: none;">
                        <?php echo htmlspecialchars($ev['lugar']); ?>
                    </td>
                    <!--<td>
                        <form method="post" style="margin:0; display:flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($ev['id']); ?>">
                            <input type="text" name="equipo" class="form-control form-control-sm" placeholder="Nombre de equipo" style="width: 150px;">
                            <button title="Registrarme" type="submit" class="btn btn-primary btn-sm text-nowrap">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Registrarme
                            </button>
                        </form>
                    </td>-->
                </tr>
                <tr>
                    <td colspan="3">
                        <?php if (!$yaRegistrado): ?>
                        <form method="post" style="margin:0; display:flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="evento_id" value="<?php echo htmlspecialchars($ev['id']); ?>">
                            <input type="text" name="equipo" class="form-control form-control-sm" placeholder="Nombre de equipo" style="width: 150px;">
                            <button title="Registrarme" type="submit" class="btn btn-primary btn-sm text-nowrap">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Registrarme
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-success fw-bold" style="font-size: 0.9em;"><i class="fa-solid fa-check"></i> Registrado</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
