<?php
include_once __DIR__ . "/../init.php";

startAPI("materia.*", ["materia", "carrera"]);

$accion = getvar('accion');
$object = new Materia();
$errors = [];

$es_admin = currentUserCan("materia.*");

$vista_titulo = "Materias";

if (checkVar("accion", 'create') && currentUserCan("materia.add_materia")) {
    if (isset($_POST['carrera_id']) && $_POST['carrera_id'] === '') {
        $_POST['carrera_id'] = null;
    }
    if (isset($_POST['periodo']) && $_POST['periodo'] === '') {
        $_POST['periodo'] = null;
    }
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: materias.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving materia: " . $e->getMessage());
        $errors[] = "Error al guardar la materia: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("materia.change_materia")) {
    $object->pk = getvar('pk');
    if (isset($_POST['carrera_id']) && $_POST['carrera_id'] === '') {
        $_POST['carrera_id'] = null;
    }
    if (isset($_POST['periodo']) && $_POST['periodo'] === '') {
        $_POST['periodo'] = null;
    }
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: materias.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving materia: " . $e->getMessage());
        $errors[] = "Error al guardar la materia: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("materia.delete_materia")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: materias.php?accion=listar');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting materia: " . $e->getMessage());
        $errors[] = "Error al eliminar la materia: " . $e->getMessage();
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
        <h1>Materias</h1>

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
            include 'app/materia/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("materia.change_materia")) {
            include 'app/materia/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("materia.add_materia")) {
            include 'app/materia/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("materia.view_materia")) {
            include 'app/materia/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
