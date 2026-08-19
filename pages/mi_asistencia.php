<?php
include_once __DIR__ . "/../init.php";

include_once __DIR__ . "/../app/asistencia_clase/model.php";

startAPI("asistencia_clase.view_mi_asistencia", ["usuario", "materia"]);

$currentUser = $_SESSION['current_user'];
$esAdmin = currentUserCan("horario.*") || currentUserCan("reporte.*");

$alumnoId = getvar('alumno_id');
if (!$alumnoId || (!$esAdmin && intval($alumnoId) !== intval($currentUser->id))) {
    $alumnoId = intval($currentUser->id);
} else {
    $alumnoId = intval($alumnoId);
}

$periodo = getvar('periodo') ?? '2026-1';

$asistenciaModel = new AsistenciaClase();
$usuarioModel = new Usuario();

$alumnoObj = new Usuario();
$alumnoObj->get($alumnoId);

$resumenMaterias = $asistenciaModel->getResumenAlumno($alumnoId, $periodo);

$todosAlumnos = $esAdmin ? $usuarioModel->getAll() : [];
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php';?>
    <title>Mi Asistencia Académica</title>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="mb-1"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Mi Progreso de Asistencias</h1>
                <p class="text-light opacity-75 mb-0">Cumplimiento del porcentaje mínimo requerido por materia</p>
            </div>

            <!-- Filtro de Alumno (Admin) y Período -->
            <form method="get" action="mi_asistencia.php" class="d-flex flex-wrap align-items-center gap-2">
                <?php if ($esAdmin): ?>
                    <div style="min-width: 240px;">
                        <select class="form-select form-select-sm select2" name="alumno_id" onchange="this.form.submit()">
                            <?php foreach ($todosAlumnos as $a): ?>
                                <option value="<?= htmlspecialchars($a['id']) ?>" <?= $alumnoId === intval($a['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(trim($a['nombre'] . ' ' . $a['apaterno'] . ' ' . ($a['amaterno'] ?? ''))) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="alumno_id" value="<?= $alumnoId ?>" />
                <?php endif; ?>

                <div style="width: 140px;">
                    <input type="text" class="form-control form-control-sm" name="periodo" placeholder="Período" value="<?= htmlspecialchars($periodo) ?>" onchange="this.form.submit()" />
                </div>

                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>
            </form>
        </div>

        <!-- Encabezado con datos del alumno -->
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-light opacity-75 d-block fs-7">Alumno / Estudiante</span>
                    <h4 class="mb-0 text-primary fw-bold"><?= htmlspecialchars((string)$alumnoObj) ?></h4>
                </div>
                <div class="text-end">
                    <span class="badge bg-info text-dark fs-6"><?= htmlspecialchars($periodo) ?></span>
                </div>
            </div>
        </div>

        <?php if (empty($resumenMaterias)): ?>
            <div class="text-center py-5 card">
                <div class="card-body">
                    <i class="fa-regular fa-folder-open fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-light">Sin materias inscritas</h5>
                    <p class="text-light opacity-75 mb-0">No se encontraron materias inscritas activas para el período <strong><?= htmlspecialchars($periodo) ?></strong>.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($resumenMaterias as $item): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0" style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title text-light mb-1 fw-bold">
                                            <?= htmlspecialchars($item['materia_nombre']) ?>
                                        </h5>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars(($item['materia_clave'] ? '[' . $item['materia_clave'] . '] ' : '') . 'Grupo ' . $item['grupo']) ?>
                                        </span>
                                    </div>
                                    <span class="badge bg-<?= $item['color'] ?> fs-6">
                                        <?= $item['badge'] ?>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2 fs-7 text-light opacity-75">
                                    <span>
                                        <i class="fa-solid fa-check-double me-1 text-primary"></i>
                                        Asistencias: <strong><?= $item['asistencias_logradas'] ?> / <?= $item['asistencias_requeridas'] ?></strong>
                                    </span>
                                    <span class="fw-bold fs-6 text-<?= $item['color'] ?>">
                                        <?= $item['porcentaje'] ?>%
                                    </span>
                                </div>

                                <!-- Barra de progreso con indicador de color (Verde >=80%, Amarillo >=60%, Rojo <60%) -->
                                <div class="progress" style="height: 12px; background-color: rgba(255,255,255,0.1); border-radius: 8px;">
                                    <div class="progress-bar bg-<?= $item['color'] ?> progress-bar-striped progress-bar-animated"
                                         role="progressbar"
                                         style="width: <?= $item['porcentaje'] ?>%; border-radius: 8px;"
                                         aria-valuenow="<?= $item['porcentaje'] ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <?php include 'templates/footer.php'; ?>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({ width: '100%' });
            }
        });
    </script>
</body>

</html>
