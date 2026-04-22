<?php
include_once __DIR__ . "/init.php";

startAPI();

include_once 'app/registro/controlador_registro.php';

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

    <main class="container flex-grow-1 d-flex flex-column py-4">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center pb-5">
            <form class="card p-4 p-md-5 shadow-lg w-100 animate-on-load delay-1" style="max-width: 600px; border-radius: 20px; background: rgba(15, 15, 20, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08);" method="post" action="registro.php" autocomplete="off">
                <div class="text-center mb-4">
                    <div class="d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, color-mix(in oklab, var(--primary) 20%, transparent), rgba(0,0,0,0.5)); border: 1px solid color-mix(in oklab, var(--primary) 50%, transparent);">
                        <i class="fa-solid fa-user-plus" style="font-size: 2.2rem; color: var(--primary); filter: drop-shadow(0 0 10px color-mix(in oklab, var(--primary) 60%, transparent));"></i>
                    </div>
                    <h2 class="mb-1 fw-bold" style="color: #fff; letter-spacing: 0.5px;">Registro</h2>
                    <p class="small m-0" style="color: rgba(255,255,255,0.6);">Crea tu cuenta para acceder al evento</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label text-light opacity-75">Nombre(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="apaterno" class="form-label text-light opacity-75">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apaterno" name="apaterno" value="<?php echo htmlspecialchars($_POST['apaterno'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="amaterno" class="form-label text-light opacity-75">Apellido Materno</label>
                        <input type="text" class="form-control" id="amaterno" name="amaterno" value="<?php echo htmlspecialchars($_POST['amaterno'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="matricula" class="form-label text-light opacity-75">Matrícula <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="matricula" name="matricula" value="<?php echo htmlspecialchars($_POST['matricula'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label text-light opacity-75">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp" class="form-label text-light opacity-75">WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo htmlspecialchars($_POST['whatsapp'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="grupo" class="form-label text-light opacity-75">Grupo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="grupo" name="grupo" value="<?php echo htmlspecialchars($_POST['grupo'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="username" class="form-label text-light opacity-75">Nombre de Usuario <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label text-light opacity-75">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirm" class="form-label text-light opacity-75">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-action-gradient w-100 mt-4 py-3"><i class="fa-solid fa-user-check me-2"></i> Registrarse</button>
                <p class="text-center mt-4 mb-0">
                    <small style="color: rgba(255,255,255,0.6);">¿Ya tienes una cuenta?</small><br>
                    <a href="index.php" class="text-decoration-none fw-bold" style="color: var(--primary); text-shadow: 0 0 8px color-mix(in oklab, var(--primary) 40%, transparent);"><i class="fa-solid fa-right-to-bracket me-1"></i> Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
