<?php
include_once __DIR__ . "/init.php";

startAPI("usuario.*", ["usuario", "perfil", ]);

$accion = getvar('accion');
$object = new Usuario();

$errors = [];

if (checkVar("accion", 'create') && currentUserCan("usuario.add_usuario")) {
    $object->fromArray($_POST);
    try {
        $object->save(true, true);
        header('Location: usuarios.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving user: " . $e->getMessage());
        $errors[] = "Error al guardar el usuario: " . $e->getMessage();
        $accion = 'crear';
    }
} elseif (checkVar("accion", 'update') && currentUserCan("usuario.change_usuario")) {
    $object->fromArray($_POST);
    $object->pk = getvar('pk');
    try {
        $object->save(true, true);
        header('Location: usuarios.php?accion=mostrar&pk=' . urlencode($object->pk));
    } catch (Exception $e) {
        error_log("Error saving user: " . $e->getMessage());
        $errors[] = "Error al guardar el usuario: " . $e->getMessage();
        $accion = 'actualizar';
    }
} elseif (checkVar("accion", 'restaurar-pwd') && currentUserCan("usuario.restaturar_contraseña_otros")) {
    $username     = trim(getvar('username') ?? '');
    $password     = getvar('password') ?? '';
    $pwd_confirm  = getvar('password_confirm') ?? '';

    if (!$username || !$password || !$pwd_confirm) {
        $errors[] = "Todos los campos son obligatorios.";
        $accion = 'restaurar-pwd';
    } elseif ($password !== $pwd_confirm) {
        $errors[] = "Las contraseñas no coinciden.";
        $accion = 'restaurar-pwd';
    } else {
        $target = $object->select(
            "username = ? OR matricula = ? OR email = ? OR TRIM(CONCAT(nombre,' ',apaterno,' ',COALESCE(amaterno,''))) = ?",
            [$username, $username, $username, $username]
        );
        if ($target === null) {
            $errors[] = "No se encontró el usuario \"" . htmlspecialchars($username) . "\".";
            $accion = 'restaurar-pwd';
        } else {
            try {
                $object->get($target['id']);
                $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
                $object->update(['password' => $nuevo_hash], 'id = ?', [$object->pk]);
                $success = "Contraseña actualizada correctamente para el usuario: " . htmlspecialchars((string)$object);
                $accion = 'restaurar-pwd';
            } catch (Exception $e) {
                error_log("Error restaurando contraseña: " . $e->getMessage());
                $errors[] = "Error al restaurar la contraseña: " . $e->getMessage();
                $accion = 'restaurar-pwd';
            }
        }
    }
} elseif (checkVar("accion", ['delete', 'eliminar']) && currentUserCan("usuario.delete_usuario")) {
    $object->pk = getvar('pk');
    try {
        $object->delete();
        header('Location: usuarios.php?accion=listar');
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        $errors[] = "Error al eliminar el usuario: " . $e->getMessage();
        $accion = 'mostrar';
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
        <h1>Usuarios</h1>

        <?php include 'templates/messages.php'; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php
        if(($accion === 'listar' || $accion === null) && currentUserCan("usuario.list_usuario")) {
            include 'app/usuario/listar.php';
        } elseif(checkVar("accion", 'actualizar') && currentUserCan("usuario.change_usuario")) {
            include 'app/usuario/actualizar.php';
        } elseif (checkVar("accion", 'crear') && currentUserCan("usuario.add_usuario")) {
            include 'app/usuario/crear.php';
        } elseif (checkVar("accion", 'mostrar') && currentUserCan("usuario.view_usuario")) {
            include 'app/usuario/mostrar.php';
        } elseif (checkVar("accion", 'restaurar-pwd') && currentUserCan("usuario.restaturar_contraseña_otros")) {
            include 'app/usuario/restaurar_pwd.php';
        } elseif (checkVar("accion", 'carga-masiva') && currentUserCan("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva.php';
        } elseif (checkVar("accion", 'add-many-step-2') && currentUserCan("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva-s2.php';
        } elseif (checkVar("accion", 'add-many-step-3') && currentUserCan("usuario.add_usuario_masivo")) {
            include 'app/usuario/carga-masiva-s3.php';
        }
        ?>

    </main>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
