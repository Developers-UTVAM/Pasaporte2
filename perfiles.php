<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';
include_once 'app/perfil/model.php';

$accion = getvar('accion');
$object = new Perfil();
$errors = [];

if ($accion === 'create') {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: perfiles.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving profile: " . $e->getMessage());
        $errors[] = "Error al guardar el perfil: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update') {
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
} elseif ($accion === 'delete' || $accion === 'eliminar') {
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
    <?php

    include 'templates/head.php';
    ?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Perfiles</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php
        if($accion === 'listar' || $accion === null) {
            include 'app/perfil/listar.php';
        } elseif($accion === 'actualizar') {
            include 'app/perfil/actualizar.php';
        } elseif ($accion === 'crear') {
            include 'app/perfil/crear.php';
        } elseif ($accion === 'mostrar') {
            include 'app/perfil/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
