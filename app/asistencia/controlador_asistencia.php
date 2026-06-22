<?php
include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../../helpers/vars.php';
include_once __DIR__ . '/modelo_asistencia.php';
include_once __DIR__ . '/../usuario/model.php';

date_default_timezone_set('America/Mexico_City');

include_once __DIR__ . '/../../helpers/session_security.php';
secure_session_start();
validate_session_fingerprint();
if (!isset($_SESSION["current_user"]) || !($_SESSION["current_user"]->can("asistencia.add_asistencia") || $_SESSION["current_user"]->can("asistencia.asistencia.*"))) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No autorizado.']);
    exit;
}

$accion = getvar('accion') ?? 'listar';
$method = $_SERVER['REQUEST_METHOD'];
$object = new Asistencia();
$errors = [];

if ($method === 'POST' && $accion === 'procesar_qr') {
    header('Content-Type: application/json');
    $evento_id = intval(getvar('evento_id'));
    $qr_data = trim((string) getvar('matricula'));

    // Validación simple: evento_id positivo y QR no vacío
    if ($evento_id <= 0 || $qr_data === '') {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos (Evento o QR).']);
        exit;
    }

    try {
        $userModel = new Usuario();
        $usuario_row = null;
        $search_value = trim($qr_data);
        $json_data = json_decode($search_value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json_data)) {
            if (isset($json_data['id'])) {
                $usuario_row = $userModel->select("id = ?", [trim($json_data['id'])]);
            } elseif (isset($json_data['matricula'])) {
                $usuario_row = $userModel->select("matricula = ?", [trim($json_data['matricula'])]);
            } elseif (isset($json_data['mat'])) {
                $usuario_row = $userModel->select("matricula = ?", [trim($json_data['mat'])]);
            }
        }
            if (!$usuario_row) {
            if (filter_var($search_value, FILTER_VALIDATE_URL)) {
                $query_string = parse_url($search_value, PHP_URL_QUERY);
                if ($query_string) {
                    parse_str($query_string, $params);
                    if (isset($params['id'])) {
                        $search_value = trim($params['id']);
                    } elseif (isset($params['mat'])) {
                        $search_value = trim($params['mat']);
                    }
                }
            }

            if (strpos($search_value, 'mat:') === 0) {
                $usuario_row = $userModel->select("matricula = ?", [trim(substr($search_value, 4))]);
            } elseif (strpos($search_value, 'id:') === 0) {
                $usuario_row = $userModel->select("id = ?", [trim(substr($search_value, 3))]);
            } else {
                $usuario_row = $userModel->select("id = ? OR matricula = ? OR username = ?", [$search_value, $search_value, $search_value]);
            }
        }

            if (!$usuario_row) {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado. Código leído: ' . htmlspecialchars($qr_data)]);
            exit;
        }
        $usuario = new Usuario();
        $usuario->get($usuario_row['id']);
        $nombre_completo = trim($usuario->nombre . ' ' . $usuario->apaterno . ' ' . $usuario->amaterno);
        if ($object->verificarRegistro($evento_id, $usuario->id)) {
            $asistio = $object->marcarAsistencia($evento_id, $usuario->id, $_SESSION["current_user"]->id);

            $kpi_hoy = $object->getTodayAttendanceCount();
            $kpi_ultimo = $object->getLatestAttendance();

            if ($asistio) {
                echo json_encode([
                    'status' => 'success',
                    'message' => "Asistencia marcada: {$nombre_completo}",
                    'kpi' => [
                        'hoy' => $kpi_hoy,
                        'ultimo_nombre' => $kpi_ultimo ? trim($kpi_ultimo['nombre'] . ' ' . $kpi_ultimo['apaterno']) : 'Ninguno',
                        'ultimo_hora' => $kpi_ultimo ? date('h:i A', strtotime($kpi_ultimo['fecha_entrada'])) : ''
                    ]
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => "¡Cuidado! {$nombre_completo} ya tiene asistencia registrada."
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'not_registered',
                'usuario_id' => $usuario->id,
                'nombre' => $nombre_completo,
                'message' => "El usuario no está inscrito. ¿Deseas realizar el auto-registro?"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;

} elseif ($method === 'POST' && $accion === 'confirmar_auto') {
    header('Content-Type: application/json');
    $evento_id = intval(getvar('evento_id'));
    $usuario_id = intval(getvar('usuario_id'));

    if ($evento_id <= 0 || $usuario_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos.']);
        exit;
    }

    try {
        $asistio = $object->autoRegistrarYAsistir($evento_id, $usuario_id, $_SESSION["current_user"]->id);

        $kpi_hoy = $object->getTodayAttendanceCount();
        $kpi_ultimo = $object->getLatestAttendance();

        if ($asistio) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuario inscrito y asistencia tomada.',
                'kpi' => [
                    'hoy' => $kpi_hoy,
                    'ultimo_nombre' => $kpi_ultimo ? trim($kpi_ultimo['nombre'] . ' ' . $kpi_ultimo['apaterno']) : 'Ninguno',
                    'ultimo_hora' => $kpi_ultimo ? date('h:i A', strtotime($kpi_ultimo['fecha_entrada'])) : ''
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El usuario ya tenía asistencia registrada en el evento.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Fallo al auto-registrar.']);
    }
    exit;
}
