<?php

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
    return sanitize_value($var);
}

function sanitize_value($v) {
    if (is_array($v)) {
        $out = [];
        foreach ($v as $k => $item) $out[$k] = sanitize_value($item);
        return $out;
    }
    if (!is_string($v)) return $v;
    $v = trim($v);
    $v = preg_replace('/[\x00-\x1F\x7F]/u', '', $v);
    return $v;
}

function getIntVar($variable): ?int {
    $value = getvar($variable);
    if ($value === null || $value === '') return null;
    if (is_array($value)) return null;
    $filtered = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $filtered === false ? null : (int) $filtered;
}

function sanitizeTeamName(string $name, int $maxLength = 100): string {
    $name = trim($name);
    if ($name === '') return '';
    $name = preg_replace('/[^\p{L}\p{N}\s\-\_\.]/u', '', $name);
    return mb_substr($name, 0, $maxLength);
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
