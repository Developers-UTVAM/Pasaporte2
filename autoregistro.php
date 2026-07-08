<?php
include_once __DIR__ . "/init.php";

startAPI("login");

$errors = [];
$eventos = [];
$mensaje = null;

try {
    if (!$_SESSION['current_user']->can('otro.autorregistrarse')) {
        $errors[] = "No tienes permiso para autorregistrarte en eventos.";
    }
} catch (Exception $e) {
    $errors[] = "No tienes permiso para autorregistrarte en eventos.";
}

if (empty($errors)) {
    $userId = $_SESSION['current_user']->id;
    $eventoId = getvar('evento_id');

    if ($eventoId !== null) {
        $tblRegistro = new Table('registro');
        $tblEvento = new Table('evento');

        try {

            $yaRegistrado = $tblRegistro->select('usuario_id = ? AND evento_id = ?', [$userId, $eventoId]);

            if ($yaRegistrado !== null) {
                $mensaje = ['tipo' => 'info', 'texto' => 'Ya estás registrado en este evento.'];
            } else {
                $equipo = getvar('equipo') ?? '';
                $datosInsert = ['usuario_id' => $userId, 'evento_id' => $eventoId];
                if ($equipo !== '') {
                    $datosInsert['equipo'] = $equipo;
                }
                $res = $tblRegistro->insert($datosInsert);
                if ($res !== false) {
                    $mensaje = ['tipo' => 'success', 'texto' => 'Tu registro se guardó correctamente.'];
                } else {
                    $errors[] = 'No se pudo completar el registro.';
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Error al registrar: ' . $e->getMessage();
        }
    }

    try {
        $tblEvento = new Table('evento');
        $now = date('Y-m-d H:i:s');
        $eventos = $tblEvento->selectAll('fecha_hora >= ? and permitir_autorregistro = 1 ORDER BY fecha_hora, nombre', [$now]);
    } catch (Exception $e) {
        $errors[] = 'Error al obtener eventos: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <title>Autoregistro</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column py-4">
        <div class="text-center mb-5 animate-on-load">
            <h2 class="mb-2"><span class="shiny-title fw-bold"><i class="fa-solid fa-calendar-plus me-2"></i> Registro a Eventos</span></h2>
            <p class="text-light opacity-75 fs-5">Inscríbete a las próximas actividades de la Semana de TICs</p>
        </div>

        <?php include 'templates/messages.php'; ?>

        <?php if ($mensaje): ?>
            <?php
                // Adaptamos el color de la alerta al tema de cristal
                $bg_color = $mensaje['tipo'] === 'success' ? 'rgba(25, 135, 84, 0.1)' : 'rgba(13, 202, 240, 0.1)';
                $border_color = $mensaje['tipo'] === 'success' ? 'rgba(25, 135, 84, 0.3)' : 'rgba(13, 202, 240, 0.3)';
                $text_color = $mensaje['tipo'] === 'success' ? 'var(--color-green-400)' : 'var(--color-blue-400)';
                $icon = $mensaje['tipo'] === 'success' ? 'fa-circle-check' : 'fa-circle-info';
            ?>
            <div class="alert alert-dismissible fade show shadow-sm mx-auto animate-on-load delay-1" role="alert" style="max-width: 900px; background: <?php echo $bg_color; ?>; border: 1px solid <?php echo $border_color; ?>; color: <?php echo $text_color; ?>; border-radius: 16px;">
                <i class="fa-solid <?php echo $icon; ?> me-2"></i> <?php echo htmlspecialchars($mensaje['texto']); ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="animate-on-load delay-2 w-100 mx-auto" style="max-width: 900px;">
            <?php include 'app/usuario/autoregistro.php'; ?>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
