<?php
session_start();

// IMPORTANTE: Usamos el helper 'db.php' que ya tiene tu proyecto para conectar a la BD.
// Esto carga automáticamente la configuración y la conexión.
require_once '../../helpers/db.php'; 

$accion = $_POST['accion'] ?? '';

if ($accion === 'registro') {
    // Recibir datos
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $matricula = $_POST['matricula'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';

    // Validar
    if (empty($email) || empty($password) || empty($matricula)) {
        die("Error: Faltan datos obligatorios.");
    }

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Instanciamos la tabla 'usuario' usando el helper del proyecto
        $usuarioTable = new Table('usuario');

        // Preparamos los datos para insertar
        // Nota: Asignamos el email también al campo 'username'
        $datosUsuario = [
            'username' => $email,
            'password' => $passwordHash,
            'nombre' => $nombre,
            'email' => $email,
            'matricula' => $matricula,
            'grupo' => $grupo,
            'whatsapp' => $whatsapp,
            'activo' => 1
        ];

        // Insertamos (el método insert devuelve el ID del nuevo registro)
        $nuevoId = $usuarioTable->insert($datosUsuario);

        // Iniciar sesión automáticamente con el ID que nos devolvió la base de datos
        $_SESSION['id'] = $nuevoId;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['matricula'] = $matricula;

        header("Location: ../../index.php");
        exit;

    } catch (Exception $e) {
        die("Error al registrar: " . $e->getMessage());
    }

} elseif ($accion === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $usuarioTable = new Table('usuario');
        
        // Buscamos al usuario por email usando el helper
        // El método select devuelve un array asociativo con los datos o null
        $usuario = $usuarioTable->select('email = ?', [$email]);

        // Verificar contraseña
        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['matricula'] = $usuario['matricula'] ?? null;
            
            header("Location: ../../index.php");
            exit;
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='login.php';</script>";
        }
    } catch (Exception $e) {
        die("Error de sistema: " . $e->getMessage());
    }
}
?>