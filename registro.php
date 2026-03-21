<?php
session_start();
include_once 'app/registro/controlador_registro.php';

// Si el usuario ya está logueado, lo redirigimos al inicio
if (isset($_SESSION["current_user"]) && $_SESSION["current_user"]) {
    header('Location: index.php');
    exit();
}

$errors = registrar_usuario();

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <title>Registro de Usuario</title>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center">
            <form class="p-4 rounded shadow custom-border" style="width: 100%; max-width: 500px;" method="post" action="registro.php" autocomplete="off">
                <h1 class="text-center mb-4">Registro de Nuevo Usuario</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="apaterno" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apaterno" name="apaterno" value="<?php echo htmlspecialchars($_POST['apaterno'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="amaterno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="amaterno" name="amaterno" value="<?php echo htmlspecialchars($_POST['amaterno'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="matricula" class="form-label">Matrícula <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="matricula" name="matricula" value="<?php echo htmlspecialchars($_POST['matricula'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo htmlspecialchars($_POST['whatsapp'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="grupo" class="form-label">Grupo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="grupo" name="grupo" value="<?php echo htmlspecialchars($_POST['grupo'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="username" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Registrarse</button>
                <p class="text-center mt-3">
                    ¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a>.
                </p>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>