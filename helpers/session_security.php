<?php

function secure_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 8 * 3600) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['created'] = time();
    }
}
function validate_session_fingerprint(): void {
    if (!isset($_SESSION['user_agent']) || !isset($_SESSION['ip'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        return;
    }
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua_prefix = mb_substr($_SESSION['user_agent'], 0, 40);
    if ($ua_prefix !== '' && strpos($ua, $ua_prefix) !== 0) {
        session_unset();
        session_destroy();
        header("Location: " . ROOT_URL . "index.php");
        exit();
    }
    if ($ip !== '' && strpos($_SESSION['ip'], '.') !== false && strpos($ip, '.') !== false) {
        $s = explode('.', $_SESSION['ip']);
        $c = explode('.', $ip);
        if (count($s) >= 2 && count($c) >= 2) {
            if ($s[0] !== $c[0] || $s[1] !== $c[1]) {
                session_unset();
                session_destroy();
                header("Location: " . ROOT_URL . "index.php");
                exit();
            }
        }
    }
}

function regenerate_session_on_login(): void {
    session_regenerate_id(true);
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
    $_SESSION['created'] = time();
}
