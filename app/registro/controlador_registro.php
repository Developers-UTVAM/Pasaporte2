<?php
include_once __DIR__ . '/../../helpers/vars.php';
include_once __DIR__ . '/../usuario/model.php';
include_once __DIR__ . '/../perfil/model.php';

function registrar_usuario(): array
{
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = new Usuario();
        
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['nombre']) || empty($_POST['apaterno']) || empty($_POST['whatsapp']) || empty($_POST['grupo']) || empty($_POST['matricula'])) {
            $errors[] = "Nombre, apellido paterno, matrícula, correo electrónico, whatsapp, grupo, nombre de usuario y contraseña son obligatorios.";
        } elseif ($_POST['password'] !== $_POST['password_confirm']) {
            $errors[] = "Las contraseñas no coinciden.";
        } else {
            $existingUser = new Usuario();
            if ($existingUser->select("username = ?", [$_POST['username']])) {
                $errors[] = "El nombre de usuario ya está en uso.";
            }
            $existingUser = new Usuario();
            if ($existingUser->select("email = ?", [$_POST['email']])) {
                $errors[] = "El correo electrónico ya está en uso.";
            }

            if (empty($errors)) {
                $data = $_POST;
                $data['superusuario'] = 0;
                $data['activo'] = 1;
                $data['categoria'] = 'basico y alumno';
                
                unset($data['password_confirm']);

                $usuario->fromArray($data);

                try {
                    if ($usuario->save()) {
                        // Asignar perfiles "basico" y "alumno"
                        $perfilModel = new Perfil();
                        $perfiles_a_asignar = ['basico', 'alumno'];
                        
                        $utp_table = new Table('usuario_tiene_perfil');

                        foreach($perfiles_a_asignar as $nombre_perfil) {
                            $perfil = $perfilModel->select('nombre = ?', [$nombre_perfil]);
                            if ($perfil) {
                                $utp_table->insert([
                                    'usuario_id' => $usuario->pk,
                                    'perfil_id' => $perfil['id']
                                ]);
                            }
                        }
                        $usuario->authenticate($_POST['username'], $_POST['password']);
                        header('Location: index.php');
                        exit();
                    } else {
                        $errors[] = "Hubo un error al crear la cuenta. Por favor, inténtelo de nuevo.";
                    }
                } catch (Exception $e) {
                    error_log("Error creating user during registration: " . $e->getMessage());
                    $errors[] = "Error al guardar el usuario: " . $e->getMessage();
                }
            }
        }
    }
    return $errors;
}