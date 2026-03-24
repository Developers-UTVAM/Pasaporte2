<?php
include_once __DIR__ . '/../../app/usuario/model.php';
include_once __DIR__ . '/modelo_olvide_mi_contrasena.php';
include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../../helpers/vars.php';

function procesar_cambio_password() {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pwd_actual = getvar('pwd_actual');
        $pwd_nuevo = getvar('pwd_nuevo');
        $pwd_confirmar = getvar('pwd_confirmar');

        if (empty($pwd_actual) || empty($pwd_nuevo) || empty($pwd_confirmar)) {
            $errors[] = "Todos los campos son obligatorios.";
        } elseif ($pwd_nuevo !== $pwd_confirmar) {
            $errors[] = "La nueva contraseña y su confirmación no coinciden.";
        } else {
            $usuario = new Usuario();
            $usuario->get($_SESSION["current_user"]->id);

            if (!password_verify($pwd_actual, $usuario->password)) {
                $errors[] = "La contraseña actual es incorrecta.";
            } else {
                $modelo = new ModeloOlvideMiContrasena();
                try {
                    if ($modelo->actualizar_password($usuario->id, $pwd_nuevo)) {
                        $usuario->logout();
                        header('Location: index.php?mensaje=' . urlencode('Tu contraseña se actualizó correctamente. Por favor, inicia sesión de nuevo.'));
                        exit;
                    } else {
                        $errors[] = "No se pudo actualizar la contraseña. Revisa la configuración de BD.";
                    }
                } catch (Exception $e) {
                    $errors[] = "Error de servidor: " . $e->getMessage();
                }
            }
        }
    }
    return $errors;
}
