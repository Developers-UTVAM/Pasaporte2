<?php
include_once __DIR__ . "/init.php";

startAPI("otro.update_perfil");

$accion = getvar('accion');
$errors = [];

if ($accion === 'update') {
    $camposPermitidos = ['nombre', 'apaterno', 'amaterno', 'categoria', 'grupo', 'email', 'whatsapp'];

    $object = new Usuario();
    $object->get($_SESSION["current_user"]->pk);

    $datosActualizar = [];
    foreach ($camposPermitidos as $campo) {
        if (isset($_POST[$campo])) {
            $datosActualizar[$campo] = $_POST[$campo];
        }
    }
    $object->fromArray($datosActualizar);

    try {
        $object->save();

        $object->logout();
        header('Location: index.php?updated=1');
        exit();
    } catch (Exception $e) {
        error_log("Error actualizando perfil propio: " . $e->getMessage());
        $errors[] = "Error al guardar los cambios: " . $e->getMessage();
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
        <div class="flex-grow-1 d-flex justify-content-center align-items-center pb-5">
            <div class="w-100 mx-auto animate-on-load delay-1" style="max-width: 800px;">
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-dismissible fade show shadow-sm mb-4" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
                <?php include 'app/usuario/editar_mi_perfil.php'; ?>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
