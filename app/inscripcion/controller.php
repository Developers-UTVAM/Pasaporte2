<?php

include_once 'helpers/vars.php';
include_once 'app/inscripcion/model.php';

$accion = getvar('accion') ?? 'listar';
$method = $_SERVER['REQUEST_METHOD'];
$object = new Inscripcion();
$errors = [];

if ($method === 'POST' && $accion === 'inscribir') {
    $materiaId = intval(getvar('materia_id') ?? 0);
    $grupo = trim(getvar('grupo') ?? '');
    $periodo = trim(getvar('periodo') ?? '');
    $usuarioIds = isset($_POST['usuario_ids']) ? (array)$_POST['usuario_ids'] : [];

    if ($materiaId <= 0 || $grupo === '' || $periodo === '' || empty($usuarioIds)) {
        $errors[] = 'Selecciona la materia, grupo, periodo y al menos un alumno.';
        $accion = 'inscribir';
    } else {
        try {
            $result = $object->inscribirMasivo($usuarioIds, $materiaId, $grupo, $periodo);
            header('Location: inscripciones.php?ok=inscribir&nuevos=' . $result['nuevos'] . '&dup=' . $result['duplicados']);
            exit;
        } catch (Exception $e) {
            error_log('Error al inscribir alumnos: ' . $e->getMessage());
            $errors[] = 'No fue posible guardar las inscripciones.';
            $accion = 'inscribir';
        }
    }
} elseif ($method === 'POST' && $accion === 'carga_masiva') {
    $materiaId = intval(getvar('materia_id') ?? 0);
    $grupo = trim(getvar('grupo') ?? '');
    $periodo = trim(getvar('periodo') ?? '');
    $usuarioIds = [];

    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Selecciona un archivo CSV válido.';
    } elseif (($handle = fopen($_FILES['archivo']['tmp_name'], 'r')) === false) {
        $errors[] = 'No fue posible leer el archivo CSV.';
    } else {
        $usuarios = $object->getAlumnos();
        $porUsername = [];
        foreach ($usuarios as $usuario) {
            $porUsername[strtolower(trim($usuario['username']))] = (int)$usuario['id'];
        }
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $valor = trim((string)($row[0] ?? ''));
            if ($valor === '' || strtolower($valor) === 'usuario_id' || strtolower($valor) === 'username') continue;
            $usuarioIds[] = ctype_digit($valor) ? (int)$valor : ($porUsername[strtolower($valor)] ?? 0);
        }
        fclose($handle);
    }

    if ($materiaId <= 0 || $grupo === '' || $periodo === '' || empty($usuarioIds)) {
        $errors[] = 'Selecciona la materia, grupo, periodo y proporciona alumnos en el CSV.';
        $accion = 'carga_masiva';
    } else {
        try {
            $result = $object->inscribirMasivo($usuarioIds, $materiaId, $grupo, $periodo);
            header('Location: inscripciones.php?ok=inscribir&nuevos=' . $result['nuevos'] . '&dup=' . $result['duplicados']);
            exit;
        } catch (Exception $e) {
            error_log('Error en carga masiva de inscripciones: ' . $e->getMessage());
            $errors[] = 'No fue posible procesar el archivo CSV.';
            $accion = 'carga_masiva';
        }
    }
} elseif ($method === 'GET' && $accion === 'baja') {
    try {
        $object->darDeBaja(intval(getvar('id') ?? 0));
        header('Location: inscripciones.php?ok=baja');
        exit;
    } catch (Exception $e) {
        error_log('Error al dar de baja inscripción: ' . $e->getMessage());
        $errors[] = 'No fue posible dar de baja la inscripción.';
        $accion = 'listar';
    }
}