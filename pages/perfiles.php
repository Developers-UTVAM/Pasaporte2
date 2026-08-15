<?php
include_once __DIR__ . "/../init.php";

startAPI("perfil.*", "perfil");

$accion = getvar('accion');
$object = new Perfil();
$errors = [];

if (checkVar("accion", 'create') && currentUserCan("perfil.add_perfil")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: perfiles.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving profile: " . $e->getMessage());
        $errors[] = "Error al guardar el perfil: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("perfil.change_perfil")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: perfiles.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving profile: " . $e->getMessage());
        $errors[] = "Error al guardar el perfil: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("perfil.delete_perfil")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: perfiles.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting profile: " . $e->getMessage());
        $errors[] = "Error al eliminar el perfil: " . $e->getMessage();
        $accion = 'mostrar';
    }
}
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php'; ?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Perfiles</h1>

        <?php include 'templates/messages.php'; ?>

        <?php
        if(($accion === 'listar' || $accion === null) && currentUserCan("perfil.list_perfil")) {
            include 'app/perfil/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("perfil.change_perfil")) {
            include 'app/perfil/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("perfil.add_perfil")) {
            include 'app/perfil/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("perfil.view_perfil")) {
            include 'app/perfil/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
