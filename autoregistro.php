<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/app/usuario/model.php';
session_start();

date_default_timezone_set('America/Mexico_City');
include_once __DIR__ . '/helpers/db.php';
include_once __DIR__ . '/helpers/vars.php';

$errors = [];
$eventos = [];
$mensaje = null;


if (!isset($_SESSION['current_user']) || !$_SESSION['current_user']) {
    $errors[] = "Debe iniciar sesión para registrarse a un evento.";
} else {
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
        $eventos = $tblEvento->selectAll('fecha_hora >= ? ORDER BY fecha_hora ASC', [$now]);
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
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1>Autoregistro</h1>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?>" role="alert">
                <?php echo htmlspecialchars($mensaje['texto']); ?>
            </div>
        <?php endif; ?>

        <?php include 'app/usuario/autoregistro.php'; ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>