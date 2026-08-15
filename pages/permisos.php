<?php
include_once __DIR__ . "/../init.php";

startAPI("permiso.*", "permiso");

$accion = getvar('accion');
$object = new Permiso();
$errors = [];

if (checkVar("accion", 'create') && currentUserCan("permiso.add_permiso")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: permisos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving permission: " . $e->getMessage());
        $errors[] = "Error al guardar el permiso: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("permiso.change_permiso")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: permisos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving permission: " . $e->getMessage());
        $errors[] = "Error al guardar el permiso: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("permiso.delete_permiso")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: permisos.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting permission: " . $e->getMessage());
        $errors[] = "Error al eliminar el permiso: " . $e->getMessage();
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
        <h1>Permisos</h1>

        <?php include 'templates/messages.php'; ?>

        <?php
        if(($accion === 'listar' || $accion === null) && currentUserCan("permiso.list_permiso")) {
            include 'app/permiso/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("permiso.change_permiso")) {
            include 'app/permiso/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("permiso.add_permiso")) {
            include 'app/permiso/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("permiso.view_permiso")) {
            include 'app/permiso/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
