<?php

require_once(PROJECT_ROOT . '/php/funcs/base_func.php');
require_once(PROJECT_ROOT . '/php/funcs/session_func.php');

function insererVol(array $values)
{
    appendToSessionArray('vols', $values);


}

function recupererVolsEnregistres() {
    return getFromSession('vols', []);
}

function recupererDernierVolEnregistre() {
    if (existsInSession('vols')) {
        $vols = getFromSession('vols');
        return $vols[count($vols) - 1];
    }
    return null;
}

function resetVolsEnregistres() {
    getAndDeleteFromSession('vols');
}