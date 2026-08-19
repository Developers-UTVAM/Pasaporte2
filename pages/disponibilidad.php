<?php
include_once __DIR__ . "/../init.php";

include_once __DIR__ . "/../app/horario/disponibilidad_model.php";
startAPI("horario.manage_disponibilidad", ["usuario"]);

$accion = getvar('accion');
$object = new ProfesorDisponibilidad();
$errors = [];

$vista_titulo = "Disponibilidad de Profesores";

if (checkVar("accion", 'create') && currentUserCan("horario.manage_disponibilidad")) {
    $profesorId = intval($_POST['profesor_id'] ?? 0);
    $dia = $_POST['dia_semana'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $periodo = $_POST['periodo'] ?? '';

    if ($object->tieneConflicto($profesorId, $dia, $horaInicio, $horaFin, $periodo)) {
        $errors[] = "El bloque de horario ya se solapa con otro registro existente para este profesor en el período $periodo.";
        $accion = 'crear';
    } else {
        $object->fromArray($_POST);
        try {
            $object->save();
            header('Location: disponibilidad.php?mensaje=' . urlencode('Bloque de disponibilidad guardado correctamente.'));
            exit;
        } catch (Exception $e) {
            error_log("Error saving disponibilidad: " . $e->getMessage());
            $errors[] = "Error al guardar el bloque de disponibilidad: " . $e->getMessage();
            $accion = 'crear';
        }
    }
} elseif (checkVar("accion", 'update') && currentUserCan("horario.manage_disponibilidad")) {
    $pk = getvar('pk');
    $profesorId = intval($_POST['profesor_id'] ?? 0);
    $dia = $_POST['dia_semana'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $periodo = $_POST['periodo'] ?? '';

    if ($object->tieneConflicto($profesorId, $dia, $horaInicio, $horaFin, $periodo, intval($pk))) {
        $errors[] = "El bloque de horario ya se solapa con otro registro existente para este profesor en el período $periodo.";
        $accion = 'actualizar';
    } else {
        $object->fromArray($_POST);
        $object->pk = $pk;
        try {
            $object->save();
            header('Location: disponibilidad.php?mensaje=' . urlencode('Bloque de disponibilidad actualizado correctamente.'));
            exit;
        } catch (Exception $e) {
            error_log("Error saving disponibilidad: " . $e->getMessage());
            $errors[] = "Error al actualizar el bloque de disponibilidad: " . $e->getMessage();
            $accion = 'actualizar';
        }
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("horario.manage_disponibilidad")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: disponibilidad.php?mensaje=' . urlencode('Bloque eliminado correctamente.'));
        exit;
    } catch (Exception $e) {
        error_log("Error deleting disponibilidad: " . $e->getMessage());
        $errors[] = "Error al eliminar el bloque: " . $e->getMessage();
        $accion = 'listar';
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
        <h1>Disponibilidad de Profesores</h1>

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
            include 'app/horario/disponibilidad_listar.php';
        } elseif (checkVar("accion", 'actualizar') && currentUserCan("horario.manage_disponibilidad")) {
            include 'app/horario/disponibilidad_form.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("horario.manage_disponibilidad")) {
            include 'app/horario/disponibilidad_form.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
    <script src="<?php echo ROOT_URL; ?>assets/js/disponibilidad.js"></script>
</body>

</html>
