<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';
include_once 'app/permiso/model.php';

if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->can("permiso.*")) {
    header("Location: index.php");
    exit();
}

$accion = getvar('accion');
$object = new Permiso();
$errors = [];

if ($accion === 'create' && $_SESSION["current_user"]->can("permiso.add_permiso")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: permisos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving permission: " . $e->getMessage());
        $errors[] = "Error al guardar el permiso: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update' && $_SESSION["current_user"]->can("permiso.change_permiso")) {
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
} elseif (($accion === 'delete' || $accion === 'eliminar') && $_SESSION["current_user"]->can("permiso.delete_permiso")) {
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
    <?php

    include 'templates/head.php';
    ?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Permisos</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php
        if(($accion === 'listar' || $accion === null) && $_SESSION["current_user"]->can("permiso.list_permiso")) {
            include 'app/permiso/listar.php';
        } elseif($accion === 'actualizar' && $_SESSION["current_user"]->can("permiso.change_permiso")) {
            include 'app/permiso/actualizar.php';
        } elseif ($accion === 'crear' && $_SESSION["current_user"]->can("permiso.add_permiso")) {
            include 'app/permiso/crear.php';
        } elseif ($accion === 'mostrar' && $_SESSION["current_user"]->can("permiso.view_permiso")) {
            include 'app/permiso/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
