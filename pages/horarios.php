<?php
include_once __DIR__ . "/../init.php";

startAPI("horario.*", ["horario", "materia", "aula", "usuario"]);

$accion = getvar('accion');
$object = new Horario();
$errors = [];

$es_admin = currentUserCan("horario.*");
$vista_titulo = "Horarios Académicos";

if (checkVar("accion", 'create') && currentUserCan("horario.add_horario")) {
    $_POST['activo'] = isset($_POST['activo']) ? 1 : 0;
    if (isset($_POST['aula_id']) && $_POST['aula_id'] === '') {
        $_POST['aula_id'] = null;
    }
    $object->fromArray($_POST);
    
    $aulaId = $_POST['aula_id'] ? intval($_POST['aula_id']) : null;
    $dia = $_POST['dia_semana'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $periodo = $_POST['periodo'] ?? '';

    if ($aulaId && !$object->verificarDisponibilidad($aulaId, $dia, $horaInicio, $horaFin, $periodo)) {
        $errors[] = "El aula seleccionada ya está ocupada en ese mismo día y horario para el período $periodo.";
        $accion = 'crear';
    } else {
        try {
            $object->save();
            header('Location: horarios.php?accion=mostrar&pk=' . urlencode($object->pk));
            exit;
        } catch (Exception $e) {
            error_log("Error saving horario: " . $e->getMessage());
            $errors[] = "Error al guardar el horario: " . $e->getMessage();
            $accion = 'crear';
        }
    }
} elseif (checkVar("accion", 'update') && currentUserCan("horario.change_horario")) {
    $object->pk = getvar('pk');
    $_POST['activo'] = isset($_POST['activo']) ? 1 : 0;
    if (isset($_POST['aula_id']) && $_POST['aula_id'] === '') {
        $_POST['aula_id'] = null;
    }
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    
    $aulaId = $_POST['aula_id'] ? intval($_POST['aula_id']) : null;
    $dia = $_POST['dia_semana'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $periodo = $_POST['periodo'] ?? '';

    if ($aulaId && !$object->verificarDisponibilidad($aulaId, $dia, $horaInicio, $horaFin, $periodo, intval($object->pk))) {
        $errors[] = "El aula seleccionada ya está ocupada en ese mismo día y horario para el período $periodo.";
        $accion = 'actualizar';
    } else {
        try {
            $object->save();
            header('Location: horarios.php?accion=mostrar&pk=' . urlencode($object->pk));
            exit;
        } catch (Exception $e) {
            error_log("Error saving horario: " . $e->getMessage());
            $errors[] = "Error al guardar el horario: " . $e->getMessage();
            $accion = 'actualizar';
        }
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("horario.delete_horario")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: horarios.php?accion=listar');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting horario: " . $e->getMessage());
        $errors[] = "Error al eliminar el horario: " . $e->getMessage();
        $accion = 'mostrar';
    }
}
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php';?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Horarios Académicos</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php $mensaje = getvar('mensaje'); if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php
        if (($accion === 'listar' || $accion === null)) {
            include 'app/horario/listar.php';
        } elseif (checkVar("accion", 'actualizar') && currentUserCan("horario.change_horario")) {
            include 'app/horario/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("horario.add_horario")) {
            include 'app/horario/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("horario.view_horario")) {
            include 'app/horario/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
