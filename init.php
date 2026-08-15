<?php
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

if (!defined('ROOT_URL')) {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $inPages = str_contains($scriptName, '/pages/');
    $inSubdir = $inPages || str_contains($scriptName, '/migraciones_db/');
    define('ROOT_URL', $inSubdir ? '../' : '');
    define('PAGES_URL', $inPages ? '' : ($inSubdir ? '../pages/' : 'pages/'));
}

function startAPI($permissions = null, $models = null) {
    loadModels("usuario");
    include_once __DIR__ . "/helpers/session_security.php";
    secure_session_start();
    validate_session_fingerprint();
    date_default_timezone_set('America/Mexico_City');

    include_once __DIR__ . "/helpers/vars.php";
    include_once __DIR__ . "/helpers/db.php";

    if($permissions) {
        if(strtolower($permissions) == "login") {
            requireLogin();
        } else {
            requirePermission($permissions);
        }
    }

    if($models) {
        loadModels($models);
    }
}

function requireLogin() {
    if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->is_authenticated()) {
        header("Location: " . ROOT_URL . "index.php");
        exit();
    }
}

function currentUserCan($permissions) {
    return $_SESSION["current_user"]->can($permissions);
}

function requirePermission($permissions) {
    requireLogin();
    if (!currentUserCan($permissions)) {
        header("Location: " . ROOT_URL . "index.php");
        exit();
    }
}

function loadModels($models) {
    if(is_array($models)) {
        foreach($models as $model) {
            loadModels($model);
        }
    } else {
        include_once __DIR__ . "/app/$models/model.php";
    }
}
