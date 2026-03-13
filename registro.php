<?php
include_once "app/usuario/model.php";
session_start();

include_once "helpers/vars.php";


include "app/usuario/controlador_registro.php";
?>
<!DOCTYPE html>
include_once 'app/registro/controller.php';
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column justify-content-center">
        <div class="row justify-content-center my-4">
            <div class="col-12 col-lg-8">
                <form class="p-4 border rounded shadow bg-white" action="registro.php" method="post" autocomplete="off">
                <div class="text-center">
                    <img src="assets/img/Registro.png" alt="Registro de Usuario" class="img-fluid" style="max-height: 80px;">
                </div>

                    <h2 class="text-center mb-4">Registro</h2>
                    
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endforeach; ?>

                    <input type="hidden" name="accion" value="registrar">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" required class="form-control" id="username" name="username" placeholder="Usuario" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                                <label for="username">Usuario (Login)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="password" required class="form-control" id="password" name="password" placeholder="Contraseña">
                                <label for="password">Contraseña</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3"><div class="form-floating"><input type="text" required class="form-control" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"><label>Nombre</label></div></div>
                        <div class="col-md-4 mb-3"><div class="form-floating"><input type="text" required class="form-control" name="apaterno" placeholder="Apellido Paterno" value="<?php echo htmlspecialchars($_POST['apaterno'] ?? ''); ?>"><label>Apellido Paterno</label></div></div>
                        <div class="col-md-4 mb-3"><div class="form-floating"><input type="text" class="form-control" name="amaterno" placeholder="Apellido Materno" value="<?php echo htmlspecialchars($_POST['amaterno'] ?? ''); ?>"><label>Apellido Materno</label></div></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-floating"><input type="number" required class="form-control" name="matricula" placeholder="Matrícula" value="<?php echo htmlspecialchars($_POST['matricula'] ?? ''); ?>"><label>Matrícula</label></div></div>
                        <div class="col-md-6 mb-3"><div class="form-floating"><input type="text" required class="form-control" name="grupo" placeholder="Grupo" value="<?php echo htmlspecialchars($_POST['grupo'] ?? ''); ?>"><label>Grupo</label></div></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-floating"><input type="email" required class="form-control" name="email" placeholder="E-Mail" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><label>E-Mail</label></div></div>
                        <div class="col-md-6 mb-3"><div class="form-floating"><input type="tel" required class="form-control" name="whatsapp" placeholder="WhatsApp" value="<?php echo htmlspecialchars($_POST['whatsapp'] ?? ''); ?>"><label>WhatsApp (10 dígitos)</label></div></div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <?php include 'templates/footer.php'; ?>
</body>
</html>
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
