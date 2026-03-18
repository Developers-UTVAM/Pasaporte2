<?php
include_once 'app/usuario/model.php';
include_once 'app/Olvidar-contrasena/modelo_recuperacion.php';
function enviar_correo_smtp($destinatario, $asunto, $cuerpo) {
  global $smtp;
    if (empty($smtp) && file_exists(__DIR__ . '/../../configs.php')) {
        include __DIR__ . '/../../configs.php';
    }

    $smtp_host = $smtp['host'] ?? 'ssl://utvam.imagilex.com.mx';
    $smtp_port = $smtp['port'] ?? 465;
    $smtp_user = $smtp['usuario'] ?? '';
    $smtp_pass = $smtp['contrasena'] ?? '';

    $socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 15);
    if (!$socket) {
        error_log("Error de conexión SMTP: $errno - $errstr");
        return false;
    }

    $leer_respuesta = function() use ($socket) {
        $res = '';
        while ($str = fgets($socket, 4096)) {
            $res .= $str;
            if (substr($str, 3, 1) == ' ') break;
        }
        return $res;
    };

    $leer_respuesta();
    fwrite($socket, "EHLO localhost\r\n"); $leer_respuesta();
    fwrite($socket, "AUTH LOGIN\r\n"); $leer_respuesta();
    fwrite($socket, base64_encode($smtp_user) . "\r\n"); $leer_respuesta();
    fwrite($socket, base64_encode($smtp_pass) . "\r\n"); $leer_respuesta();
    fwrite($socket, "MAIL FROM: <$smtp_user>\r\n"); $leer_respuesta();
    fwrite($socket, "RCPT TO: <$destinatario>\r\n"); $leer_respuesta();
    fwrite($socket, "DATA\r\n"); $leer_respuesta();

    $headers = "MIME-Version: 1.0\r\nContent-type: text/plain; charset=utf-8\r\nFrom: Sistema Pasaporte UTVAM <$smtp_user>\r\nTo: <$destinatario>\r\nSubject: =?utf-8?B?" . base64_encode($asunto) . "?=\r\n";
    fwrite($socket, $headers . "\r\n" . $cuerpo . "\r\n.\r\n"); $leer_respuesta();
    fwrite($socket, "QUIT\r\n"); fclose($socket);
    return true;
}

function procesar_solicitud_recuperacion() {
    $mensajes = ['error' => '', 'exito' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identificador = trim($_POST['identificador'] ?? '');

        if (empty($identificador)) {
            $mensajes['error'] = 'Por favor, ingresa tu usuario o correo electrónico.';
            return $mensajes;
        }

        $modelo_recuperacion = new ModeloRecuperacion();
        $usuario_id = $modelo_recuperacion->buscar_usuario_por_identificador($identificador);

        if ($usuario_id) {
            $usuario = new Usuario();
            $usuario->get($usuario_id);

            $token = bin2hex(random_bytes(32));
            $expira_en = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $modelo_recuperacion->guardar_token($usuario_id, $token, $expira_en);

            $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
            $enlace = $protocolo . $_SERVER['HTTP_HOST'] . "/restablecer_password.php?token=" . $token;
            $asunto = "Recuperación de contraseña";
            $cuerpo = "Hola " . $usuario->nombre . ",\n\nPara restablecer tu contraseña, haz clic en el siguiente enlace:\n" . $enlace . "\n\nEste enlace expira en 1 hora.";
            enviar_correo_smtp($usuario->email, $asunto, $cuerpo);
        }
        $mensajes['exito'] = 'Si el usuario o correo existe y está activo, te hemos enviado un enlace para restablecer tu contraseña.';
    }

    return $mensajes;
}

function procesar_restablecimiento_password($token) {
    $mensajes = ['error' => '', 'exito' => '', 'token_valido' => false];

    if (empty($token)) {
        $mensajes['error'] = 'Enlace de recuperación no válido.';
        return $mensajes;
    }

    $modelo_recuperacion = new ModeloRecuperacion();
    $usuario_id = $modelo_recuperacion->validar_token($token);

    if (!$usuario_id) {
        $mensajes['error'] = 'El enlace es inválido o ha expirado.';
        return $mensajes;
    }

    $mensajes['token_valido'] = true;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($password) || empty($password_confirm)) {
            $mensajes['error'] = 'Por favor, completa todos los campos.';
        } elseif ($password !== $password_confirm) {
            $mensajes['error'] = 'Las contraseñas no coinciden.';
        } else {
            $modelo_recuperacion->actualizar_password($usuario_id, $password);

            $modelo_recuperacion->eliminar_token($token);

            $mensajes['exito'] = 'Tu contraseña ha sido restablecida exitosamente.';
            $mensajes['token_valido'] = false;
        }
    }

    return $mensajes;
}
