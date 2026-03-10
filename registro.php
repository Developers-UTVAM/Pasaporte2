<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';
include_once 'app/registro/model.php';

$accion = getvar('accion') ?? 'listar';
$method = $_SERVER['REQUEST_METHOD'];
$object = new Registro();
$errors = [];

if ($method === 'POST' && $accion === 'crear') {
    $evento_id   = intval(getvar('evento_id') ?? 0);
    $usuario_ids = isset($_POST['usuario_ids']) ? (array)$_POST['usuario_ids'] : [];

    if ($evento_id <= 0) {
        $errors[] = "Debes seleccionar un evento.";
        $accion = 'crear';
    } elseif (empty($usuario_ids)) {
        $errors[] = "Debes seleccionar al menos un usuario.";
        $accion = 'crear';
    } else {
        try {
            $r = $object->crearMasivo($evento_id, $usuario_ids);
            header("Location: registro.php?accion=listar&ok=masivo&nuevos={$r['nuevos']}&dup={$r['duplicados']}");
            exit;
        } catch (Exception $e) {
            error_log("Error registro masivo: " . $e->getMessage());
            $errors[] = "Error al guardar: " . $e->getMessage();
            $accion = 'crear';
        }
    }

} elseif ($method === 'POST' && $accion === 'editar') {
    $registros_sel = isset($_POST['registros_sel']) ? (array)$_POST['registros_sel'] : [];
    $tipo_accion   = getvar('tipo_accion') ?? 'mover';
    $nuevo_evento  = intval(getvar('nuevo_evento_id') ?? 0);

    if (empty($registros_sel)) {
        $errors[] = "Debes seleccionar al menos un registro.";
        $accion = 'editar';
    } elseif ($tipo_accion === 'mover' && $nuevo_evento <= 0) {
        $errors[] = "Debes seleccionar el evento destino.";
        $accion = 'editar';
    } else {
        try {
            $movidos = 0; $omitidos = 0; $eliminados = 0;

            foreach ($registros_sel as $par) {
                $partes = explode('|', $par);
                if (count($partes) !== 2) continue;
                $ev_id  = intval($partes[0]);
                $usr_id = intval($partes[1]);
                if ($ev_id <= 0 || $usr_id <= 0) continue;

                if ($tipo_accion === 'eliminar') {
                    $object->eliminar($ev_id, $usr_id);
                    $eliminados++;
                } else {
                    if ($nuevo_evento === $ev_id) { $omitidos++; continue; }
                    $object->eliminar($ev_id, $usr_id);
                    if ($object->crear($nuevo_evento, $usr_id)) {
                        $movidos++;
                    } else {
                        $omitidos++;
                    }
                }
            }

            if ($tipo_accion === 'eliminar') {
                header("Location: registro.php?accion=listar&ok=elim&n={$eliminados}");
            } else {
                header("Location: registro.php?accion=listar&ok=mover&n={$movidos}&om={$omitidos}");
            }
            exit;
        } catch (Exception $e) {
            error_log("Error editar masivo: " . $e->getMessage());
            $errors[] = "Error al procesar: " . $e->getMessage();
            $accion = 'editar';
        }
    }

} elseif ($method === 'GET' && $accion === 'eliminar') {
    $evento_id  = intval(getvar('evento_id')  ?? 0);
    $usuario_id = intval(getvar('usuario_id') ?? 0);
    try {
        $object->eliminar($evento_id, $usuario_id);
        header('Location: registro.php?accion=listar&ok=elim&n=1');
        exit;
    } catch (Exception $e) {
        error_log("Error deleting registro: " . $e->getMessage());
        $errors[] = "Error al eliminar: " . $e->getMessage();
        $accion = 'listar';
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
        <h1>Registros a Eventos</h1>

        <?php
        $ok = getvar('ok') ?? '';
        if ($ok === 'masivo'):
            $n = intval(getvar('nuevos') ?? 0);
            $d = intval(getvar('dup')    ?? 0);
        ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-circle-check"></i>
                <strong><?= $n ?> registro(s) creado(s) correctamente.</strong>
                <?php if ($d > 0): ?>
                    &nbsp;(<?= $d ?> ya estaban inscritos y se omitieron.)
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($ok === 'mover'):
            $n  = intval(getvar('n')  ?? 0);
            $om = intval(getvar('om') ?? 0);
        ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-right-left"></i>
                <strong><?= $n ?> registro(s) movido(s) al nuevo evento.</strong>
                <?php if ($om > 0): ?>
                    &nbsp;(<?= $om ?> omitido(s): ya estaban en el evento destino.)
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($ok === 'elim'):
            $n = intval(getvar('n') ?? 0);
        ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fa-solid fa-ban"></i>
                <strong><?= $n ?> registro(s) eliminado(s).</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; ?>

        <?php
        if ($accion === 'crear') {
            include 'app/registro/crear.php';
        } elseif ($accion === 'editar') {
            include 'app/registro/editar.php';
        } else {
            include 'app/registro/listar.php';
        }
        ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
