<?php
include_once __DIR__ . "/../init.php";

include_once __DIR__ . "/../app/horario/disponibilidad_model.php";

startAPI("horario.view_carga_academica", ["horario", "usuario"]);

$currentUser = $_SESSION['current_user'];
$esAdmin = currentUserCan("horario.*");

$profesorId = getvar('profesor_id');
if (!$profesorId || (!$esAdmin && intval($profesorId) !== intval($currentUser->id))) {
    $profesorId = intval($currentUser->id);
} else {
    $profesorId = intval($profesorId);
}

$periodo = getvar('periodo') ?? '2026-1';

$horarioModel = new Horario();
$usuarioModel = new Usuario();

$profesorObj = new Usuario();
$profesorObj->get($profesorId);

$horariosProfesor = $horarioModel->getHorariosPorProfesor($profesorId, $periodo);
$horasTotales = $horarioModel->getCargaProfesor($profesorId, $periodo);

$todosProfesores = $esAdmin ? $usuarioModel->getAll() : [];
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php';?>
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/modules/horarios.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="mb-1"><i class="fa-solid fa-calendar-week me-2 text-primary"></i>Carga Académica</h1>
                <p class="text-light opacity-75 mb-0">Horario semanal de clases asignadas</p>
            </div>

            <!-- Filtros de Profesor y Período -->
            <form method="get" action="carga_academica.php" class="d-flex flex-wrap align-items-center gap-2">
                <?php if ($esAdmin): ?>
                    <div style="min-width: 220px;">
                        <select class="form-select form-select-sm select2" name="profesor_id" onchange="this.form.submit()">
                            <?php foreach ($todosProfesores as $p): ?>
                                <option value="<?= htmlspecialchars($p['id']) ?>" <?= $profesorId === intval($p['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(trim($p['nombre'] . ' ' . $p['apaterno'] . ' ' . ($p['amaterno'] ?? ''))) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="profesor_id" value="<?= $profesorId ?>" />
                <?php endif; ?>

                <div style="width: 140px;">
                    <input type="text" class="form-control form-control-sm" name="periodo" placeholder="Período" value="<?= htmlspecialchars($periodo) ?>" onchange="this.form.submit()" />
                </div>

                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>
            </form>
        </div>

        <!-- KPI Carga Horaria -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="kpi-carga-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-light opacity-75 d-block fs-7">Carga Semanal Total</span>
                        <h3 class="mb-0 text-primary fw-bold"><?= $horasTotales ?> hrs/semana</h3>
                    </div>
                    <div class="fs-2 text-primary opacity-50">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8 mt-3 mt-md-0">
                <div class="kpi-carga-card d-flex align-items-center">
                    <i class="fa-solid fa-user-tie fs-3 text-info me-3"></i>
                    <div>
                        <span class="text-light opacity-75 d-block fs-7">Docente Seleccionado</span>
                        <h5 class="mb-0 text-light fw-bold"><?= htmlspecialchars((string)$profesorObj) ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'app/horario/carga_academica.php'; ?>

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
