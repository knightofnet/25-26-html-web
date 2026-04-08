<?php
function saveInSession(string $key, $value)
{
    $_SESSION['saved'][$key] = $value;
}

function existsInSession(string $string)
{
    return isset($_SESSION['saved'][$string]);
}

function getAndDeleteFromSession(string $key, $defaultValue = null)
{
    if (isset($_SESSION['saved'][$key])) {
        $value = $_SESSION['saved'][$key];
        unset($_SESSION['saved'][$key]);
        return $value;
    }
    return $defaultValue;
}

function getFromSession(string $key, $defaultValue = null) {
    if (isset($_SESSION['saved'][$key])) {
        return $_SESSION['saved'][$key];
    }
    return $defaultValue;

}

function appendToSessionArray(string $key, $value) {
    if (!isset($_SESSION['saved'][$key]) || !is_array($_SESSION['saved'][$key])) {
        $_SESSION['saved'][$key] = [];
    }
    $_SESSION['saved'][$key][] = $value;
}