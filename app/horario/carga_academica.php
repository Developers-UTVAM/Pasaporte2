<?php
$diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

$diasNombres = [
    'lunes' => 'Lunes',
    'martes' => 'Martes',
    'miercoles' => 'Miércoles',
    'jueves' => 'Jueves',
    'viernes' => 'Viernes',
    'sabado' => 'Sábado'
];

$horariosPorDia = [
    'lunes' => [],
    'martes' => [],
    'miercoles' => [],
    'jueves' => [],
    'viernes' => [],
    'sabado' => []
];

foreach ($horariosProfesor as $h) {
    $dia = strtolower($h['dia_semana']);
    if (array_key_exists($dia, $horariosPorDia)) {
        $horariosPorDia[$dia][] = $h;
    }
}
?>

<div class="schedule-grid-container">
    <?php if (empty($horariosProfesor)): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-light">No hay clases asignadas</h5>
            <p class="text-light opacity-75 mb-0">El docente no tiene horarios registrados para el período <strong><?= htmlspecialchars($periodo) ?></strong>.</p>
        </div>
    <?php else: ?>
        <table class="schedule-table">
            <thead>
                <tr>
                    <?php foreach ($diasSemana as $d): ?>
                        <th>
                            <i class="fa-solid fa-calendar-day me-1 text-primary"></i>
                            <?= $diasNombres[$d] ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($diasSemana as $d): ?>
                        <td>
                            <?php if (empty($horariosPorDia[$d])): ?>
                                <div class="text-center py-4 text-light opacity-50 fs-7">
                                    <em>Sin clase</em>
                                </div>
                            <?php else: ?>
                                <?php foreach ($horariosPorDia[$d] as $clase): ?>
                                    <div class="class-card">
                                        <div class="class-title">
                                            <?= htmlspecialchars($clase['materia_nombre']) ?>
                                        </div>
                                        <div class="class-info mb-1">
                                            <i class="fa-solid fa-clock me-1 text-info"></i>
                                            <?= htmlspecialchars(date('H:i', strtotime($clase['hora_inicio']))) ?> - <?= htmlspecialchars(date('H:i', strtotime($clase['hora_fin']))) ?>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-1 mt-2">
                                            <span class="badge-group" title="Grupo">
                                                Gpo <?= htmlspecialchars($clase['grupo']) ?>
                                            </span>
                                            <?php if (!empty($clase['aula_codigo'])): ?>
                                                <span class="badge-aula" title="Aula">
                                                    <i class="fa-solid fa-door-open me-1"></i><?= htmlspecialchars($clase['aula_codigo']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
