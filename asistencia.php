<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/db.php';
include_once 'helpers/vars.php';
include_once 'app/asistencia/modelo_asistencia.php';
include_once 'app/evento/model.php';

if (!isset($_SESSION["current_user"]) || (!$_SESSION["current_user"]->can("asistencia.view_asistencia") && !$_SESSION["current_user"]->can("asistencia.asistencia.*"))) {
    header("Location: index.php");
    exit();
}

$accion = getvar('accion');
$object = new Asistencia();
$errors = [];
$mensaje = getvar('mensaje');

$eventos = [];
if ($_SESSION["current_user"]->can("asistencia.add_asistencia") || $_SESSION["current_user"]->can("asistencia.asistencia.*")) {
    $eventos = $object->getTodosEventos();
}

$asistencias_hoy = $object->getTodayAttendanceCount();
$ultimo_registro = $object->getLatestAttendance();

if ($accion === 'eliminar' && ($_SESSION["current_user"]->can("asistencia.delete_asistencia") || $_SESSION["current_user"]->can("asistencia.asistencia.*"))) {
    try {
        $evento_id = getvar('evento_id');
        $usuario_id = getvar('usuario_id');

        $object->eliminarAsistencia($evento_id, $usuario_id);
        header('Location: asistencia.php?accion=listar&mensaje=' . urlencode('Registro de asistencia eliminado.'));
        exit;
    } catch (Exception $e) {
        $errors[] = "Error al eliminar asistencia: " . $e->getMessage();
        $accion = 'listar';
    }
}

?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php'; ?>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>#qr-reader video { border-radius: 10px; object-fit: cover; }</style>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <div class="row mb-4 pt-3 align-items-center">
            <div class="col-12 text-center text-md-start">
                <h1 class="mb-2"><span class="colores-gay big-text"><i class="fa-solid fa-clipboard-user"></i> Módulo de Asistencia</span></h1>
                <p class="mt-2 mb-0" style="color: var(--text-color); opacity: 0.8;">Gestiona el acceso y registra la asistencia de los participantes.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="kpi-card glass-panel">
                    <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="kpi-info">
                        <span class="kpi-title">Asistencias Hoy</span>
                        <h3 class="kpi-value" id="kpi-asistencias-hoy"><?= $asistencias_hoy ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kpi-card glass-panel">
                    <div class="kpi-icon" style="color: var(--color-blue-400); background: rgba(59, 130, 246, 0.1);"><i class="fa-solid fa-calendar-day"></i></div>
                    <div class="kpi-info">
                        <span class="kpi-title">Eventos Activos</span>
                        <h3 class="kpi-value"><?= count($eventos); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kpi-card glass-panel">
                    <div class="kpi-icon" style="color: var(--color-green-400); background: rgba(16, 185, 129, 0.1);"><i class="fa-solid fa-check-double"></i></div>
                    <div class="kpi-info">
                        <span class="kpi-title">Último Registro</span>
                        <div id="kpi-ultimo-registro-container">
                            <?php if ($ultimo_registro): ?>
                                <h3 class="kpi-value" style="font-size: 1.1rem; margin-top: 2px; line-height: 1.2;">
                                    <?= htmlspecialchars($ultimo_registro['nombre'] . ' ' . $ultimo_registro['apaterno']) ?>
                                </h3>
                                <small class="text-muted-custom" style="font-size: 0.75rem;"><?= date('h:i A', strtotime($ultimo_registro['fecha_entrada'])) ?></small>
                            <?php else: ?>
                                <h3 class="kpi-value" style="font-size: 1.2rem; margin-top: 5px;">Ninguno</h3>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>

        <?php if ($mensaje): ?>
            <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.3); color: var(--color-green-400); border-radius: 16px;">
                <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php
        if ($accion === 'listar' || $accion === null || $accion === 'escanear' || $accion === 'manual') {
            include 'app/asistencia/listar.php';
        } elseif ($accion === 'mostrar') {
            include 'app/asistencia/mostrar.php';
        }
        ?>
    </main>
    <div class="modal fade" id="modalRegistroAsistencia" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header border-0 pb-0">
            <h2 class="modal-title" id="modalRegistroLabel"><i class="fa-solid fa-clipboard-check me-2"></i> Capturar Asistencia</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="detenerEscaneo()"></button>
          </div>
          <div class="modal-body p-4" style="color: var(--text-color);">
            <div id="modal-alert-container"></div>

            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="form-floating">
                        <select id="evento_id_global" class="form-select" style="background-color: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-color); border-radius: 16px;" required>
                            <option value="">— Selecciona un evento —</option>
                            <?php foreach($eventos as $e): ?>
                                <option value="<?= htmlspecialchars($e['id']) ?>">
                                    <?= htmlspecialchars($e['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="evento_id_global" style="color: rgba(255,255,255,0.7);"><i class="fa-solid fa-calendar-check text-primary me-1"></i> 1. Evento Destino</label>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills nav-justified mb-4 mx-auto" style="max-width: 400px; gap: 10px;" id="registroTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active btn btn-outline-primary w-100" id="qr-tab" data-bs-toggle="pill" data-bs-target="#qr-pane" type="button" role="tab" aria-selected="true">
                        <i class="fa-solid fa-qrcode me-1"></i> Escáner
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-outline-secondary w-100" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual-pane" type="button" role="tab" aria-selected="false" onclick="detenerEscaneo()">
                        <i class="fa-solid fa-keyboard me-1"></i> Manual
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="registroTabsContent">
                <div class="tab-pane fade show active text-center" id="qr-pane" role="tabpanel" aria-labelledby="qr-tab">
                    <div class="form-check form-switch d-flex justify-content-center align-items-center gap-2 mb-4">
                        <input class="form-check-input mt-0" type="checkbox" role="switch" id="modoContinuo" checked style="width: 40px; height: 20px; cursor: pointer;">
                        <label class="form-check-label m-0" for="modoContinuo" style="cursor: pointer;">Modo Continuo</label>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary px-4" id="btn-iniciar-qr" onclick="iniciarEscaneo()">
                            <i class="fa-solid fa-camera me-2"></i> Activar Escáner
                        </button>
                        <button class="btn btn-secondary px-4 d-none" style="color: #ff4d4d; border-color: #ff4d4d;" id="btn-detener-qr" onclick="detenerEscaneo()">
                            <i class="fa-solid fa-stop me-2"></i> Detener Cámara
                        </button>
                    </div>

                    <div id="qr-reader-container" class="mx-auto mb-3" style="width: 100%; max-width: 350px; display: none; border-radius: 24px; overflow: hidden; border: 1px solid var(--glass-border); background: var(--glass-bg);">
                        <div id="qr-reader" style="width: 100%; border: none;"></div>
                    </div>

                    <div id="qr-status" class="mt-3 fs-5 mx-auto" style="max-width: 500px; min-height: 40px;"></div>

                    <div id="lista-escaneados" class="mt-4 text-start mx-auto" style="max-width: 450px; display: none;">
                        <h6 class="text-primary fw-bold text-uppercase small mb-3"><i class="fa-solid fa-clock-rotate-left me-1"></i> Historial Reciente:</h6>
                        <ul class="list-group list-group-flush shadow-sm" style="border-radius: 12px; border: 1px solid var(--glass-border); overflow: hidden;" id="ul-escaneados"></ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="manual-pane" role="tabpanel" aria-labelledby="manual-tab">
                    <form autocomplete="off" method="post" action="asistencia.php" id="form-manual">
                        <input type="hidden" name="accion" value="marcar" />
                        <input type="hidden" name="evento_id" id="evento_id_manual_hidden" value="" />
                        <div class="row justify-content-center mt-2 mb-4">
                            <div class="col-md-10 text-center">
                                <div class="form-floating mb-4">
                                    <input type="text" name="usuario_id" id="usuario_id_manual" class="form-control" required placeholder="Ingresar matrícula o ID" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-color); border-radius: 16px;">
                                    <label for="usuario_id_manual" style="color: rgba(255,255,255,0.7);"><i class="fa-solid fa-id-card me-1"></i> Matrícula del Usuario</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" style="border-radius: 16px; font-size: 1.1rem; font-weight: bold; padding: 0.8rem;" onclick="return syncEventoManual()">
                                    <i class="fa-solid fa-check-to-slot me-2"></i> Registrar Manualmente
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <audio id="audio-qr" src="assets/sounds/qr.mp3" preload="auto"></audio>

    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/asistencia.js"></script>
</body>
</html>
