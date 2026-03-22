<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';
include_once 'app/usuario/model.php';
include_once 'app/perfil/model.php';

$accion = getvar('accion');
$object = new Usuario();

if ($accion === 'logout' || $accion === 'salir') {
    $object->logout();
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->can("usuario.*")) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($accion === 'create' && $_SESSION["current_user"]->can("usuario.add_usuario")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: usuarios.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving user: " . $e->getMessage());
        $errors[] = "Error al guardar el usuario: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update' && $_SESSION["current_user"]->can("usuario.change_usuario")) {
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
} elseif (($accion === 'delete' || $accion === 'eliminar') && $_SESSION["current_user"]->can("usuario.delete_usuario")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: usuarios.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        $errors[] = "Error al eliminar el usuario: " . $e->getMessage();
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
        <h1>Usuarios</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php
        if(($accion === 'listar' || $accion === null) && $_SESSION["current_user"]->can("usuario.list_usuario")) {
            include 'app/usuario/listar.php';
        } elseif($accion === 'actualizar' && $_SESSION["current_user"]->can("usuario.change_usuario")) {
            include 'app/usuario/actualizar.php';
        } elseif ($accion === 'crear' && $_SESSION["current_user"]->can("usuario.add_usuario")) {
            include 'app/usuario/crear.php';
        } elseif ($accion === 'mostrar' && $_SESSION["current_user"]->can("usuario.view_usuario")) {
            include 'app/usuario/mostrar.php';
        } elseif ($accion === 'carga-masiva' && $_SESSION["current_user"]->can("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva.php';
        } elseif ($accion === 'add-many-step-2' && $_SESSION["current_user"]->can("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva-s2.php';
        } elseif ($accion === 'add-many-step-3' && $_SESSION["current_user"]->can("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva-s3.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
