<?php
session_start();

include_once 'helpers/vars.php';
include_once 'app/evento/model.php';

$accion = getvar('accion');
$object = new Evento();
$errors = [];

if ($accion === 'create') {
    $object->fromArray($_POST);
    try {
        $object->save();
        header('Location: eventos.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving event: " . $e->getMessage());
        $errors[] = "Error al guardar el evento: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif ($accion === 'update') {
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
} elseif ($accion === 'delete' || $accion === 'eliminar') {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: eventos.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting event: " . $e->getMessage());
        $errors[] = "Error al eliminar el evento: " . $e->getMessage();
        $accion = 'mostrar';
    }
} elseif ($accion === 'guardar_registro') {
    $eventoId = getvar('pk');
    $selected = getvar('usuarios');
    if (!is_array($selected)) {
        if ($selected === null) {
            $selected = [];
        } else {
            $selected = [$selected];
        }
    }
    $selected = array_map('intval', $selected);

    try {
        $registroTable = new Table('usuario_tiene_evento');
        $existing = $registroTable->selectAll('evento_id = ?', [$eventoId]);
        $existingIds = array_map(fn($r) => (int)$r['usuario_id'], $existing);

        $toAdd = array_diff($selected, $existingIds);
        $toRemove = array_diff($existingIds, $selected);

        foreach ($toAdd as $usuarioId) {
            $registroTable->insert(['usuario_id' => $usuarioId, 'evento_id' => $eventoId]);
        }
        foreach ($toRemove as $usuarioId) {
            $registroTable->delete('evento_id = ? AND usuario_id = ?', [$eventoId, $usuarioId]);
        }

        header('Location: eventos.php?accion=registro&pk=' . urlencode($eventoId));
        exit;
    } catch (Exception $e) {
        error_log("Error saving event registrations: " . $e->getMessage());
        $errors[] = "Error al guardar los registros: " . $e->getMessage();
        $accion = 'registro';
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

        <?php
        if($accion === 'disponibles') {
            include 'app/evento/disponibles.php';
        } elseif($accion === 'registro') {
            include 'app/evento/registro.php';
        } elseif($accion === 'listar' || $accion === null) {
            include 'app/evento/listar.php';
        } elseif($accion === 'actualizar') {
            include 'app/evento/actualizar.php';
        } elseif ($accion === 'crear') {
            include 'app/evento/crear.php';
        } elseif ($accion === 'mostrar') {
            include 'app/evento/mostrar.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>