<?php
include_once 'app/usuario/model.php';
session_start();

include_once 'helpers/vars.php';
include_once 'app/registroevento/model.php';

$method  = $_SERVER['REQUEST_METHOD'];
$object  = new Registro();
$errors  = [];
$success = null;

if ($method === 'POST') {
	$evento_id   = intval(getvar('evento_id') ?? 0);
	$usuario_ids = isset($_POST['usuario_ids']) ? (array)$_POST['usuario_ids'] : [];

	if ($evento_id <= 0) {
		$errors[] = 'Debes seleccionar un evento.';
	} elseif (empty($usuario_ids)) {
		$errors[] = 'Debes seleccionar al menos un usuario.';
	} else {
		try {
			$success = $object->crearMasivo($evento_id, $usuario_ids);
		} catch (Exception $e) {
			error_log('Error registro rapido: ' . $e->getMessage());
			$errors[] = 'Error al guardar: ' . $e->getMessage();
		}
	}
}

if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->can("registrorapidoevento.*")) {
    header("Location: index.php");
    exit();
}

?><!DOCTYPE html>
<html lang="es-MX">
<head>
	<?php include 'templates/head.php'; ?>
</head>
<body>
	<?php include 'templates/header.php'; ?>

	<main class="container">
		<h1>Registro Rápido a Eventos</h1>

		<?php if ($success !== null): ?>
			<div class="alert alert-success alert-dismissible fade show">
				<i class="fa-solid fa-circle-check"></i>
				<strong><?= $success['nuevos'] ?> usuario(s) registrado(s) correctamente.</strong>
				<?php if ($success['duplicados'] > 0): ?>
					&nbsp;(<?= $success['duplicados'] ?> ya estaban inscritos y se omitieron.)
				<?php endif; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		<?php endif; ?>

		<?php foreach ($errors as $error): ?>
			<div class="alert alert-danger">
				<i class="fa-solid fa-triangle-exclamation"></i>
				<?= htmlspecialchars($error) ?>
			</div>
		<?php endforeach; ?>

		<?php include 'app/registrorapidoevento/crear.php'; ?>
	</main>

	<?php include 'templates/footer.php'; ?>
</body>
</html>
