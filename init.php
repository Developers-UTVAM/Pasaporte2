<?php
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
        header("Location: index.php");
        exit();
    }
}

function currentUserCan($permissions) {
    return $_SESSION["current_user"]->can($permissions);
}

function requirePermission($permissions) {
    requireLogin();
    if (!currentUserCan($permissions)) {
        header("Location: index.php");
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
