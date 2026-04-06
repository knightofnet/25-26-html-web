<?php
function estValideDate($date)
{
    return preg_match('/^((0[1-9])|([1-2]\d)|(3[0-1]))\/((0[1-9])|(1[0-2]))\/[1-2]\d{3}$/', $date);
}

function estValideHeure($date)
{
    return preg_match('/^(([0-1]\d)|(2[0-3])):([0-5]\d)$/', $date);
}

function estValideSelect($value, array $choixPossibles)
{
    return in_array($value, $choixPossibles);
}

function estValideMajMinChiffres($chaine)
{
    return preg_match('/^[A-Za-z0-9]+$/', $chaine);
}

function estValideEntierPos($chaine)
{
    return preg_match('/^[0-9]+$/', $chaine);
}

function estPresenteEtValide($key, array $values, array $msgErreur)
{
    return isset($values[$key]) && !isset($msgErreur[$key]);
}

function afficheMsgErreur($clef, array $msgErreur)
{
    if (isset($msgErreur[$clef])) {
        return '<p style="color: yellow; background-color: red">' . $msgErreur[$clef] . '</p>';
    }
    return '';
}

function afficheValue(array $values, $clef)
{
    if (isset($values[$clef])) {
        return 'value="' . $values[$clef] . '"';
    }

    return '';
}

function afficheOptionSelected(array $values, $clef, $valueOption)
{
    if (isset($values[$clef])) {
        if ($values[$clef] === $valueOption) {
            return 'selected="selected"';
        }
    }
    return '';
}