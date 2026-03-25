<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/db.php';
include_once 'helpers/vars.php';
include_once 'app/evento/model.php';

if (!isset($_SESSION["current_user"])) {
    header("Location: index.php");
    exit();
}

if (!$_SESSION["current_user"]->can("evento.*") && 
    $accion !== null && $accion !== 'listar' && $accion !== 'mostrar') {
    header("Location: index.php");
    exit();
}

$accion = getvar('accion');
$object = new Evento();
$errors = [];


$es_admin = $_SESSION["current_user"]->can("evento.*");

if ($accion === 'listar_expirados' && $es_admin) {
    $data = $object->getEventosExpirados();
    $vista_titulo = "Eventos Expirados";
} else {
    $data = $object->getEventosPorRol($es_admin);
    $vista_titulo = $es_admin ? "Todos los Eventos" : "Eventos Disponibles";
}


if ($accion === 'create' && $_SESSION["current_user"]->can("evento.add_evento")) {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: eventos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving event: " . $e->getMessage());
        $errors[] = "Error al guardar el evento: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update' && $_SESSION["current_user"]->can("evento.change_evento")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save();
        header('Location: eventos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving event: " . $e->getMessage());
        $errors[] = "Error al guardar el evento: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (($accion === 'delete' || $accion === 'eliminar') && $_SESSION["current_user"]->can("evento.delete_evento")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: eventos.php?accion=listar');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting event: " . $e->getMessage());
        $errors[] = "Error al eliminar el evento: " . $e->getMessage();
        $accion = 'mostrar';
    }
} elseif ($accion === 'autoregistrar') {
    if (!isset($_SESSION['current_user']) || !$_SESSION['current_user']) {
        $errors[] = "Debe iniciar sesión para registrarse a un evento.";
        $accion = 'listar';
    } else {
        $userId = $_SESSION['current_user']->id;
        $eventoId = getvar('evento_id');
        if ($eventoId !== null) {
            $tblRegistro = new Table('registro');
            try {
                $yaRegistrado = $tblRegistro->select('usuario_id = ? AND evento_id = ?', [$userId, $eventoId]);
                if ($yaRegistrado !== null) {
                    $errors[] = 'Ya estás registrado en este evento.';
                } else {
                    $res = $tblRegistro->insert(['usuario_id' => $userId, 'evento_id' => $eventoId]);
                    if ($res !== false) {
                        header('Location: eventos.php?accion=listar&mensaje=' . urlencode('Tu registro se guardó correctamente.'));
                        exit;
                    } else {
                        $errors[] = 'No se pudo completar el registro.';
                    }
                }
            } catch (Exception $e) {
                $errors[] = 'Error al registrar: ' . $e->getMessage();
            }
        }
        $accion = 'listar';
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
        <h1>Eventos</h1>

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
            include 'app/evento/listar.php';
        } elseif($accion === 'actualizar' && $_SESSION["current_user"]->can("evento.change_evento")) {
            include 'app/evento/actualizar.php';
        } elseif ($accion === 'crear' && $_SESSION["current_user"]->can("evento.add_evento")) {
            include 'app/evento/crear.php';
        } elseif ($accion === 'mostrar' && $_SESSION["current_user"]->can("evento.view_evento")) {
            include 'app/evento/mostrar.php';
        } elseif ($accion === 'carga-masiva' && $_SESSION["current_user"]->can("evento.add_evento_masivo")) {
            include 'app/evento/carga-masiva.php';
        } elseif ($accion === 'add-many-step-2' && $_SESSION["current_user"]->can("evento.add_evento_masivo")) {
            include 'app/evento/carga-masiva-s2.php';
        } elseif ($accion === 'listar_expirados' && $_SESSION["current_user"]->can("evento.*")) {
            include 'app/evento/listar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
