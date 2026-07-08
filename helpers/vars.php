<?php

if (!function_exists('sanitize_input_data')) {
    function sanitize_input_data(&$data, $is_root = true) {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if ($is_root && in_array($key, ['password', 'password_confirm'], true)) {
                    continue;
                }
                sanitize_input_data($value, false);
            }
        } elseif (is_string($data)) {
            $data = strip_tags($data);
            $data = trim($data);
        }
    }

    sanitize_input_data($_GET);
    sanitize_input_data($_POST);
    sanitize_input_data($_REQUEST);
    sanitize_input_data($_COOKIE);
}

/**
 * Gets a POST var
 * @param mixed $variable Variable name
 * @return mixed
 */
function getPOST($variable): mixed {
    return isset($_POST[$variable]) ? $_POST[$variable] : null;
}

/****
 * Gets a GET var
 * @param mixed $variable Variable name
 * @return mixed
 */
function getGET($variable): mixed {
    return isset($_GET[$variable]) ? $_GET[$variable] : null;
}

/**
 * Gets a variable from POST or GET (POST takes precedence)
 * @param mixed $variable Variable name
 * @return mixed
 */
function getvar($variable): mixed {
    $var = getPOST($variable);
    if ($var === null) {
        $var = getGET($variable);
    }
    return $var;
}

function checkVar($variable, $value, $strict_mode = false) : bool {
    $var_value = getvar($variable);
    if(!is_array($value)) {
        return $strict_mode ?
            $var_value === $value
            : $var_value !== null && (strtolower($var_value) == strtolower($value));
    } else {
        foreach($value as $v) {
            if(checkVar($variable, $v, $strict_mode)) {
                return true;
            }
        }
    }
    return false;
}
