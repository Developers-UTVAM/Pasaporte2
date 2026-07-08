<?php
include_once __DIR__ . "/init.php";

startAPI("otro.registrar_en_evento_rapido", "registroevento");

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
			$success = $object->crearMasivo($evento_id, $usuario_ids, getvar('equipo'));
		} catch (Exception $e) {
			error_log('Error registro rapido: ' . $e->getMessage());
			$errors[] = 'Error al guardar: ' . $e->getMessage();
		}
	}
}
?><!DOCTYPE html>
<html lang="es-MX">
<head>
	<?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100">
	<?php include 'templates/header.php'; ?>

	<main class="container flex-grow-1 d-flex flex-column py-4">
		<div class="text-center mb-5 animate-on-load">
			<h2 class="mb-2"><span class="shiny-title fw-bold"><i class="fa-solid fa-bolt me-2"></i> Registro Rápido</span></h2>
			<p class="text-light opacity-75 fs-5">Inscribe múltiples participantes a un evento de forma simultánea</p>
		</div>

		<?php if ($success !== null): ?>
			<div class="alert alert-dismissible fade show shadow-sm mx-auto animate-on-load delay-1" role="alert" style="max-width: 900px; background: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.3); color: var(--color-green-400); border-radius: 16px;">
				<i class="fa-solid fa-circle-check me-2"></i>
				<strong><?= $success['nuevos'] ?> usuario(s) registrado(s) correctamente.</strong>
				<?php if ($success['duplicados'] > 0): ?>
					&nbsp;(<?= $success['duplicados'] ?> ya estaban inscritos y se omitieron.)
				<?php endif; ?>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

		<?php foreach ($errors as $error): ?>
			<div class="alert alert-dismissible fade show shadow-sm mx-auto animate-on-load delay-1" role="alert" style="max-width: 900px; background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
				<i class="fa-solid fa-triangle-exclamation me-2"></i>
				<?= htmlspecialchars($error) ?>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endforeach; ?>

		<div class="animate-on-load delay-2 w-100 mx-auto" style="max-width: 900px;">
			<?php include 'app/registrorapidoevento/crear.php'; ?>
		</div>
	</main>

	<?php include 'templates/footer.php'; ?>
</body>
</html>
