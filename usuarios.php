<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';
include_once 'app/usuario/model.php';

$accion = getvar('accion');
$object = new Usuario();
$errors = [];

if ($accion === 'create') {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: usuarios.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving user: " . $e->getMessage());
        $errors[] = "Error al guardar el usuario: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update') {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: usuarios.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving user: " . $e->getMessage());
        $errors[] = "Error al guardar el usuario: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif ($accion === 'delete' || $accion === 'eliminar') {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: usuarios.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        $errors[] = "Error al eliminar el usuario: " . $e->getMessage();
        $accion = 'mostrar';
    }
} elseif ($accion === 'logout' || $accion === 'salir') {
    $object->logout();
    header('Location: index.php');
    exit();
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
        <h1>Usuarios</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php
        if($accion === 'listar' || $accion === null) {
            include 'app/usuario/listar.php';
        } elseif($accion === 'actualizar') {
            include 'app/usuario/actualizar.php';
        } elseif ($accion === 'crear') {
            include 'app/usuario/crear.php';
        } elseif ($accion === 'mostrar') {
            include 'app/usuario/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
