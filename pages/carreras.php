<?php
include_once __DIR__ . "/../init.php";

startAPI("carrera.*", "carrera");

$accion = getvar('accion');
$object = new Carrera();
$errors = [];

$es_admin = currentUserCan("carrera.*");

$data = $object->getActivas();
$vista_titulo = "Carreras";

if (checkVar("accion", 'create') && currentUserCan("carrera.add_carrera")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: carreras.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving carrera: " . $e->getMessage());
        $errors[] = "Error al guardar la carrera: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("carrera.change_carrera")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: carreras.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving carrera: " . $e->getMessage());
        $errors[] = "Error al guardar la carrera: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("carrera.delete_carrera")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: carreras.php?accion=listar');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting carrera: " . $e->getMessage());
        $errors[] = "Error al eliminar la carrera: " . $e->getMessage();
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
        <h1>Carreras</h1>

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
       if(($accion === 'listar' || $accion === null)) {
            include 'app/carrera/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("carrera.change_carrera")) {
            include 'app/carrera/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("carrera.add_carrera")) {
            include 'app/carrera/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("carrera.view_carrera")) {
            include 'app/carrera/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
