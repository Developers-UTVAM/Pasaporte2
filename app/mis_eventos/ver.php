<?php
$config      = $pasaporte->getConfig();
$resumen     = $pasaporte->getResumen($usuario_id);
$registros   = $pasaporte->getRegistros($usuario_id);
$asistencias = $pasaporte->getAsistencias($usuario_id);

$ids_con_asistencia = array_column($asistencias, 'id');

$total_req  = array_sum(array_column($resumen, 'requerido'));
$total_asis = array_sum(array_column($resumen, 'asistencia'));
$pct        = $total_req > 0 ? min(100, round($total_asis / $total_req * 100)) : 0;
$pasaporte_completo = $pct >= 100;
?>

<style>
.event-card {
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 10px;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
}
.event-card .event-title {
    font-weight: 600;
    font-size: .95rem;
    margin-bottom: 6px;
}
.event-card .event-meta {
    font-size: .78rem;
    color: rgba(255,255,255,.55);
    margin-bottom: 2px;
}
.event-card .event-meta span {
    color: rgba(255,255,255,.85);
}
.event-card .event-status {
    margin-top: 8px;
}
@media (max-width: 767px) {
    .table-desktop { display: none !important; }
    .cards-mobile  { display: block !important; }
}
@media (min-width: 768px) {
    .table-desktop { display: block !important; }
    .cards-mobile  { display: none !important; }
}
</style>

<h1 class="my-4">Mis Eventos</h1>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold text-secondary text-uppercase" style="letter-spacing:.06em; font-size:.85rem;">Progreso general</span>
            <span class="fw-bold <?= $pasaporte_completo ? 'text-success' : 'text-warning' ?>">
                <?= $total_asis ?> / <?= $total_req ?> actividades
            </span>
        </div>
        <div class="progress" style="height:20px; background:#ffffff11; border-radius:20px;">
            <div class="progress-bar <?= $pasaporte_completo ? 'bg-success' : 'bg-warning' ?> fw-bold"
                 role="progressbar"
                 style="width:<?= $pct ?>%; border-radius:20px; font-size:.8rem; color:#000;"
                 aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                <?= $pct ?>%
            </div>
        </div>
        <?php if ($pasaporte_completo): ?>
            <div class="alert alert-success mt-3 mb-0 py-2">
                <i class="fa-solid fa-circle-check"></i>
                <strong>¡Felicidades! Has completado tu pasaporte de la Semana de TIC's.</strong>
            </div>
        <?php else: ?>
            <p class="text-muted small mt-2 mb-0">
                <i class="fa-solid fa-circle-info"></i>
                Recuerda que un profesor debe registrar tu asistencia en cada actividad.
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($resumen as $tipo => $d): ?>
        <?php if ($tipo === 'sin_tipo') continue; ?>
        <?php $color = $d['completo'] ? 'success' : ($d['asistencia'] > 0 ? 'warning' : 'secondary'); ?>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card glass-panel text-center h-100">
                <div class="card-body py-3 px-2">
                    <i class="fa-solid <?= $d['icono'] ?> fa-2x text-<?= $color ?> mb-2"></i>
                    <div class="text-uppercase fw-bold" style="font-size:.72rem; letter-spacing:.08em; color:#ffffffb3;"><?php
                        $tipo_cfg = $config[$tipo] ?? null;
                        $label = $tipo_cfg['label'] ?? ucfirst(str_replace('_', ' ', $tipo));
                    ?>
                    <?= htmlspecialchars($label) ?></div>
                    <span class="badge bg-<?= $color ?> fs-6 mt-1"><?= $d['asistencia'] ?>/<?= $d['requerido'] ?></span>
                    <?php if ($d['registrado'] > $d['asistencia']): ?>
                        <div class="text-info mt-1" style="font-size:.68rem;">+<?= $d['registrado'] - $d['asistencia'] ?> pendiente(s)</div>
                    <?php endif; ?>
                    <?php if ($d['completo']): ?>
                        <div class="text-success mt-1" style="font-size:.8rem;"><i class="fa-solid fa-circle-check"></i></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<ul class="nav nav-tabs mb-0" id="tabPasaporte" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-registros" type="button" role="tab">
            <i class="fa-solid fa-clipboard-list"></i> Mis Registros
            <span class="badge bg-secondary ms-1"><?= count($registros) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-asistencias" type="button" role="tab">
            <i class="fa-solid fa-circle-check"></i> Asistencias Confirmadas
            <span class="badge bg-success ms-1"><?= count($asistencias) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content card" style="border-top:none; border-radius:0 0 24px 24px;">

    <div class="tab-pane fade show active" id="tab-registros" role="tabpanel">
        <?php if (empty($registros)): ?>
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                Aún no estás registrado en ninguna actividad.
            </div>
        <?php else: ?>

            <div class="cards-mobile p-2">
                <?php foreach ($registros as $reg): ?>
                    <?php
                        $tiene_asis = in_array($reg['id'], $ids_con_asistencia);
                        $tipo_cfg   = $config[$reg['tipo']] ?? null;
                        $icono      = $tipo_cfg['icono'] ?? 'fa-calendar';
                        $label      = $tipo_cfg['label'] ?? ($reg['tipo'] ? ucfirst($reg['tipo']) : 'Sin tipo');
                    ?>
                    <div class="event-card">
                        <div class="event-title <?= $tiene_asis ? 'text-success' : '' ?>">
                            <?= htmlspecialchars($reg['nombre']) ?>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-secondary">
                                <i class="fa-solid <?= $icono ?>"></i> <?= htmlspecialchars($label) ?>
                            </span>
                            <span class="badge bg-dark text-warning">
                                <i class="fa-regular fa-calendar"></i>
                                <?= (new DateTime($reg['fecha_hora']))->format('d/m/Y H:i') ?>
                            </span>
                        </div>
                        <?php if (!empty($reg['lugar'])): ?>
                        <div class="event-meta"><i class="fa-solid fa-location-dot me-1"></i>Lugar: <span><?= htmlspecialchars($reg['lugar']) ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($reg['responsable_interno'])): ?>
                        <div class="event-meta"><i class="fa-solid fa-user-tie me-1"></i>Resp. Interno: <span><?= htmlspecialchars($reg['responsable_interno']) ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($reg['responsable_externo'])): ?>
                        <div class="event-meta"><i class="fa-solid fa-user me-1"></i>Resp. Externo: <span><?= htmlspecialchars($reg['responsable_externo']) ?></span></div>
                        <?php endif; ?>
                        <div class="event-status">
                            <?php if ($tiene_asis): ?>
                                <span class="text-success fw-semibold">
                                    <i class="fa-solid fa-circle-check"></i> Asistencia confirmada
                                </span>
                            <?php else: ?>
                                <span class="text-warning">
                                    <i class="fa-regular fa-clock"></i> Asistencia pendiente
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="table-desktop table-responsive">
                <table class="table table-hover table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Lugar</th>
                            <th>Resp. Interno</th>
                            <th>Resp. Externo</th>
                            <th class="text-center">Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg): ?>
                            <?php
                                $tiene_asis = in_array($reg['id'], $ids_con_asistencia);
                                $tipo_cfg   = $config[$reg['tipo']] ?? null;
                                $icono      = $tipo_cfg['icono'] ?? 'fa-calendar';
                                $label      = $tipo_cfg['label'] ?? ($reg['tipo'] ? ucfirst($reg['tipo']) : 'Sin tipo');
                            ?>
                            <tr>
                                <td class="<?= $tiene_asis ? 'text-success' : '' ?> fw-semibold">
                                    <?= htmlspecialchars($reg['nombre']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fa-solid <?= $icono ?>"></i> <?= htmlspecialchars($label) ?>
                                    </span>
                                </td>
                                <td class="text-nowrap text-warning"><?= (new DateTime($reg['fecha_hora']))->format('d/m/Y H:i') ?></td>
                                <td><?= htmlspecialchars($reg['lugar'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($reg['responsable_interno'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($reg['responsable_externo'] ?? '—') ?></td>
                                <td class="text-center">
                                    <?php if ($tiene_asis): ?>
                                        <span class="text-success fw-semibold">
                                            <i class="fa-solid fa-circle-check"></i> Confirmada
                                        </span>
                                    <?php else: ?>
                                        <span class="text-warning">
                                            <i class="fa-regular fa-clock"></i> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

    <div class="tab-pane fade" id="tab-asistencias" role="tabpanel">
        <?php if (empty($asistencias)): ?>
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                Aún no tienes asistencias confirmadas por ningún profesor.
            </div>
        <?php else: ?>

            <div class="cards-mobile p-2">
                <?php foreach ($asistencias as $a): ?>
                    <?php
                        $tipo_cfg = $config[$a['tipo']] ?? null;
                        $icono    = $tipo_cfg['icono'] ?? 'fa-calendar';
                        $label    = $tipo_cfg['label'] ?? ($a['tipo'] ? ucfirst($a['tipo']) : 'Sin tipo');
                    ?>
                    <div class="event-card">
                        <div class="event-title text-success">
                            <?= htmlspecialchars($a['nombre']) ?>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-success">
                                <i class="fa-solid <?= $icono ?>"></i> <?= htmlspecialchars($label) ?>
                            </span>
                            <span class="badge bg-dark text-warning">
                                <i class="fa-regular fa-calendar"></i>
                                <?= (new DateTime($a['fecha_hora']))->format('d/m/Y H:i') ?>
                            </span>
                        </div>
                        <?php if (!empty($a['responsable_interno'])): ?>
                        <div class="event-meta"><i class="fa-solid fa-user-tie me-1"></i>Resp. Interno: <span class="text-info"><?= htmlspecialchars($a['responsable_interno']) ?></span></div>
                        <?php endif; ?>
                        <?php if (!empty($a['responsable_externo'])): ?>
                        <div class="event-meta"><i class="fa-solid fa-user me-1"></i>Resp. Externo: <span><?= htmlspecialchars($a['responsable_externo']) ?></span></div>
                        <?php endif; ?>
                        <div class="event-status text-success fw-semibold">
                            <i class="fa-solid fa-circle-check"></i>
                            Confirmada: <?= (new DateTime($a['fecha_entrada']))->format('d/m/Y H:i') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="table-desktop table-responsive">
                <table class="table table-hover table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Tipo</th>
                            <th>Fecha Actividad</th>
                            <th>Resp. Interno</th>
                            <th>Resp. Externo</th>
                            <th>Asistencia registrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $a): ?>
                            <?php
                                $tipo_cfg = $config[$a['tipo']] ?? null;
                                $icono    = $tipo_cfg['icono'] ?? 'fa-calendar';
                                $label    = $tipo_cfg['label'] ?? ($a['tipo'] ? ucfirst($a['tipo']) : 'Sin tipo');
                            ?>
                            <tr class="fila-inscrita">
                                <td class="text-success fw-semibold"><?= htmlspecialchars($a['nombre']) ?></td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fa-solid <?= $icono ?>"></i> <?= htmlspecialchars($label) ?>
                                    </span>
                                </td>
                                <td class="text-nowrap text-warning"><?= (new DateTime($a['fecha_hora']))->format('d/m/Y H:i') ?></td>
                                <td>
                                    <small class="text-info">
                                        <i class="fa-solid fa-user-tie"></i>
                                        <?= htmlspecialchars($a['responsable_interno'] ?? '—') ?>
                                    </small>
                                </td>
                                <td>
                                    <small class="text-secondary">
                                        <i class="fa-solid fa-user"></i>
                                        <?= htmlspecialchars($a['responsable_externo'] ?? '—') ?>
                                    </small>
                                </td>
                                <td class="text-nowrap text-success fw-semibold">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <?= (new DateTime($a['fecha_entrada']))->format('d/m/Y H:i') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

</div>
