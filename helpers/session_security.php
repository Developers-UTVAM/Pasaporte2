<?php

function secure_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly'  => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}


function generate_session_fingerprint(): string {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    return hash('sha256', $ua);
}


function validate_session_fingerprint(): void {
    if (!isset($_SESSION['_fingerprint'])) {
        $_SESSION['_fingerprint'] = generate_session_fingerprint();
        return;
    }

    if ($_SESSION['_fingerprint'] !== generate_session_fingerprint()) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}



function regenerate_session_on_login(): void {
    session_regenerate_id(true);
    $_SESSION['_fingerprint'] = generate_session_fingerprint();
}
