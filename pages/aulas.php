<?php
include_once __DIR__ . "/../init.php";

startAPI("aula.*", "aula");

$accion = getvar('accion');
$object = new Aula();
$errors = [];

$es_admin = currentUserCan("aula.*");

$data = $object->getActivas();
$vista_titulo = "Aulas";

if (checkVar("accion", 'create') && currentUserCan("aula.add_aula")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: aulas.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving aula: " . $e->getMessage());
        $errors[] = "Error al guardar el aula: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("aula.change_aula")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: aulas.php?accion=mostrar&pk=' . urlencode($object->pk));
        exit;
    } catch (Exception $e) {
        error_log("Error saving aula: " . $e->getMessage());
        $errors[] = "Error al guardar el aula: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("aula.delete_aula")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: aulas.php?accion=listar');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting aula: " . $e->getMessage());
        $errors[] = "Error al eliminar el aula: " . $e->getMessage();
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
        <h1>Aulas</h1>

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
            include 'app/aula/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("aula.change_aula")) {
            include 'app/aula/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("aula.add_aula")) {
            include 'app/aula/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("aula.view_aula")) {
            include 'app/aula/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
