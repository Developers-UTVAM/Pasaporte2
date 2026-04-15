<?php
include_once __DIR__ . "/init.php";
//
startAPI("eventos.admin_eventos_config", ["eventos_config"]);

$accion  = getvar('accion');
$object  = new PasaporteConfig();
$errors  = [];

if (checkVar("accion", 'create')) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: administrador_eventos.php?accion=listar');
    } catch (Exception $e) {
        $errors[] = "Error al guardar: " . $e->getMessage();
        $accion   = 'crear';
    }
} elseif (checkVar("accion", 'update')) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: administrador_eventos.php?accion=listar');
    } catch (Exception $e) {
        $errors[] = "Error al guardar: " . $e->getMessage();
        $accion   = 'actualizar';
    }
} elseif (checkVar("accion", ['delete', 'eliminar'])) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: administrador_eventos.php?accion=listar');
    } catch (Exception $e) {
        $errors[] = "Error al eliminar: " . $e->getMessage();
    }
}
?><!DOCTYPE html>
<html lang="es-MX">
<head><?php include 'templates/head.php'; ?></head>
<body>
    <?php include 'templates/header.php'; ?>
    <main class="container">
        <h1><i class="fa-solid fa-id-card-clip"></i> Configuración de Eventos</h1>
        <?php include 'templates/messages.php'; ?>
        <?php
        if (checkVar("accion", 'actualizar')) {
            include 'app/eventos_config/actualizar.php';
        } elseif (checkVar("accion", 'crear')) {
            include 'app/eventos_config/crear.php';
        } else {
            include 'app/eventos_config/listar.php';
        }
        ?>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
