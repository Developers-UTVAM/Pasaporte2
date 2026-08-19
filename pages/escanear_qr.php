<?php
include_once __DIR__ . "/../init.php";

include_once __DIR__ . "/../app/asistencia_clase/model.php";

startAPI("asistencia_clase.pasar_lista", ["horario", "usuario", "aula"]);

$horarioId = getvar('horario_id');
$accion = getvar('accion');

if ($accion === 'marcar_qr' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $identificador = getvar('identificador') ?? '';
    $hId = intval(getvar('horario_id') ?? 0);
    $fecha = getvar('fecha') ?? date('Y-m-d');

    if (!$hId || !$identificador) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros requeridos.']);
        exit;
    }

    $asistenciaModel = new AsistenciaClase();
    $res = $asistenciaModel->marcarAsistenciaQR($hId, $identificador, $fecha);
    echo json_encode($res);
    exit;
}

$horarioModel = new Horario();
$horarioObj = null;
if ($horarioId) {
    $horarios = $horarioModel->getTodosEnriquecidos();
    foreach ($horarios as $h) {
        if (intval($h['id']) === intval($horarioId)) {
            $horarioObj = $h;
            break;
        }
    }
}

$todosHorarios = $horarioModel->getTodosEnriquecidos();
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php';?>
    <title>Pase de Lista con QR — Clases</title>
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/modules/qr.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="mb-1"><i class="fa-solid fa-qrcode me-2 text-primary"></i>Pase de Lista por QR</h1>
                <p class="text-light opacity-75 mb-0">Escaneo de credencial PASS-ID para clases regulares</p>
            </div>

            <!-- Selector de Horario / Clase -->
            <form method="get" action="escanear_qr.php" class="d-flex align-items-center gap-2">
                <div style="min-width: 280px;">
                    <select required class="form-select form-select-sm select2" name="horario_id" onchange="this.form.submit()">
                        <option value="">-- Seleccionar Clase / Horario --</option>
                        <?php foreach ($todosHorarios as $h): ?>
                            <option value="<?= htmlspecialchars($h['id']) ?>" <?= (intval($horarioId) === intval($h['id'])) ? 'selected' : '' ?>>
                                <?= htmlspecialchars(($h['materia_clave'] ? '[' . $h['materia_clave'] . '] ' : '') . $h['materia_nombre'] . ' - Gpo ' . $h['grupo'] . ' (' . ucfirst($h['dia_semana']) . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if (!$horarioId || !$horarioObj): ?>
            <div class="alert alert-info" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>Por favor selecciona una clase o grupo en el desplegable superior para iniciar el pase de lista.
            </div>
        <?php else: ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-primary fw-bold"><?= htmlspecialchars($horarioObj['materia_nombre']) ?></h4>
                            <p class="mb-0 text-light opacity-75">
                                <strong>Grupo:</strong> <?= htmlspecialchars($horarioObj['grupo']) ?> |
                                <strong>Profesor:</strong> <?= htmlspecialchars($horarioObj['profesor_nombre']) ?> |
                                <strong>Horario:</strong> <?= htmlspecialchars(ucfirst($horarioObj['dia_semana']) . ' ' . date('H:i', strtotime($horarioObj['hora_inicio'])) . ' - ' . date('H:i', strtotime($horarioObj['hora_fin']))) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button type="button" class="btn btn-primary" id="btn-toggle-camera" onclick="iniciarEscaneoClase()">
                                <i class="fa-solid fa-camera me-1"></i> Activar Escáner QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal / Contenedor del Escáner -->
            <div id="qr-reader-container" class="card mb-4" style="display: none;">
                <div class="card-body text-center">
                    <div id="qr-reader" class="mx-auto" style="width: 100%; max-width: 480px;"></div>
                    <button type="button" class="btn btn-outline-danger mt-3" onclick="detenerEscaneoClase()">
                        <i class="fa-solid fa-stop me-1"></i> Detener Escáner
                    </button>
                </div>
            </div>

            <div id="alert-container" class="mb-3"></div>

            <!-- Tabla de alumnos registrados en esta sesión -->
            <div class="card">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light"><i class="fa-solid fa-list-check me-2 text-success"></i>Alumnos Registrados Hoy</h5>
                    <span class="badge bg-success" id="badge-contador">0 Registrados</span>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Método</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-asistencias-body">
                            <tr id="row-sin-registros">
                                <td colspan="4" class="text-center text-muted py-3">No hay lecturas registradas en esta sesión.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <audio id="audio-qr" src="<?php echo ROOT_URL; ?>assets/sounds/qr.mp3" preload="auto"></audio>

    <?php include 'templates/footer.php'; ?>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="<?php echo ROOT_URL; ?>assets/js/asistencia_clase.js?v=<?php echo time(); ?>"></script>
</body>

</html>
