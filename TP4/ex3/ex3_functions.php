<?php
function estValideMajuscule($chaine)
{
    return preg_match('/^[A-Z]+$/', $chaine);
}

function estValideMajMinChiffres($chaine)
{
    return preg_match('/^[A-Za-z0-9]+$/', $chaine);
}

function estValideDate($date)
{
    return preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date);
}

function estValideMajChiffres($chaine)
{
    return preg_match('/^[A-Z0-9]+$/', $chaine);
}

function estValideNombrePos($chaine)
{
    return preg_match('/^[0-9]+(\.[0-9]+)?$/', $chaine);
}

function estValideEntierPos($chaine)
{
    return preg_match('/^[0-9]+$/', $chaine);
}


function estValideSelect($value, array $choixPossibles)
{
    return in_array($value, $choixPossibles);
}

function afficheMsgErreur(array $msgErreur, $clef)
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