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
<body class="d-flex flex-column vh-100 login-page">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column py-4">
        <div class="flex-grow-1 d-flex justify-content-center align-items-center pb-5 position-relative" style="z-index: 1;">
            <div class="glow-orb glow-orb-1"></div>
            <div class="glow-orb glow-orb-2"></div>

            <form class="card w-100 registro-card animate-on-load delay-1" method="post" action="registro.php" autocomplete="off">
                <div class="text-center mb-5">
                    <h2 class="fw-black mb-1" style="color:#fff; font-size:1.75rem; letter-spacing:-0.04em;">Registro</h2>
                    <p class="small m-0" style="color:rgba(255,255,255,0.45); letter-spacing:0.01em;">Crea tu cuenta para acceder al evento</p>
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
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder=" " value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                                <label for="nombre">Nombre(s) *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="apaterno" name="apaterno" placeholder=" " value="<?php echo htmlspecialchars($_POST['apaterno'] ?? ''); ?>" required>
                                <label for="apaterno">Apellido Paterno *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="amaterno" name="amaterno" placeholder=" " value="<?php echo htmlspecialchars($_POST['amaterno'] ?? ''); ?>">
                                <label for="amaterno">Apellido Materno</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-id-badge"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="matricula" name="matricula" placeholder=" " value="<?php echo htmlspecialchars($_POST['matricula'] ?? ''); ?>" required>
                                <label for="matricula">Matrícula *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder=" " value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                <label for="email">Correo Electrónico *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-brands fa-whatsapp"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder=" " value="<?php echo htmlspecialchars($_POST['whatsapp'] ?? ''); ?>" required>
                                <label for="whatsapp">WhatsApp *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-solid fa-users"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="grupo" name="grupo" placeholder=" " value="<?php echo htmlspecialchars($_POST['grupo'] ?? ''); ?>" required>
                                <label for="grupo">Grupo *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-regular fa-circle-user"></i></span>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder=" " value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                                <label for="username">Nombre de Usuario *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                                <label for="password">Contraseña *</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group-minimal">
                            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder=" " required>
                                <label for="password_confirm">Confirmar Contraseña *</label>
                            </div>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn-minimal-pill w-100 mt-4"><i class="fa-solid fa-user-check me-2"></i> Registrarse</button>
                
                <a href="index.php" class="btn-minimal-pill w-100 mt-3"><i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión</a>
            </form>
        </div>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
