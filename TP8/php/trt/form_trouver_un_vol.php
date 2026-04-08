<?php

require_once('../config.php');

require_once(PROJECT_ROOT . '/php/funcs/base_func.php');
require_once(PROJECT_ROOT . '/php/funcs/session_func.php');
require_once(PROJECT_ROOT . '/php/funcs/validation_func.php');

require_once(PROJECT_ROOT . '/php/services/vols_services.php');

$msgErreur = [];
$values = [];

if (!empty($_POST) && count($_POST) > 0) {

    /*
     * Controle et validation des données reçues dans $_POST
     */

    $field = 'dateDep';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideDate($values[$field])) {
            $msgErreur[$field] = "La date de départ n'est pas valide (format JJ/MM/AAAA attendu)";
        }
    }

    $field = 'hrDep';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideHeure($values[$field])) {
            $msgErreur[$field] = "L'heure de départ n'est pas valide (format HH:MM attendu)";
        }
    }

    $field = 'dateArr';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideDate($values[$field])) {
            $msgErreur[$field] = "La date d'arrivée n'est pas valide (format JJ/MM/AAAA attendu)";
        }
    }

    $field = 'hrArr';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideHeure($values[$field])) {
            $msgErreur[$field] = "L'heure d'arrivée n'est pas valide (format HH:MM attendu)";
        }
    }

    $field = 'classe';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideSelect($values[$field], ['eco', 'ecop', 'aff', 'pclas'])) {
            $msgErreur[$field] = "La classe n'est pas valide";
        }
    }

    $field = 'nbAdd';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideEntierPos($values[$field])) {
            $msgErreur[$field] = "Le nombre d'adulte n'est pas correct (format attendu : un nombre entier)";
        } else {
            $values[$field] = intval($values[$field]);
        }
    }

    $field = 'nbEnf';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideEntierPos($values[$field])) {
            $msgErreur[$field] = "Le nombre d'enfant n'est pas correct (format attendu : un nombre entier)";
        } else {
            $values[$field] = intval($values[$field]);
        }

    }


    /*
     * Contrôle de cohérence des données
     */

    // On vérifie que la date-heure d'arrivée est bien après la date-heure de départ (si les deux sont présentes et valides)
    if (estPresenteEtValide('dateDep', $values, $msgErreur)
        && estPresenteEtValide('hrDep', $values, $msgErreur)
        && estPresenteEtValide('dateArr', $values, $msgErreur)
        && estPresenteEtValide('hrArr', $values, $msgErreur)
    ) {

        $isDateEnErreur = false;

        // Dans un premier temps, on va réellement valider les dates :
        $dateDepart = DateTime::createFromFormat('d/m/Y H:i', $values['dateDep'] . ' ' . $values['hrDep']);
        if (DateTime::getLastErrors() !== false
            && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0)) {
            $msgErreur['dateDep'] = "Erreur de format sur la date ou l'heure de départ";
            $isDateEnErreur = true;
        }
        $dateArrivee = DateTime::createFromFormat('d/m/Y H:i', $values['dateArr'] . ' ' . $values['hrArr']);
        if (DateTime::getLastErrors() !== false
            && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0)) {
            $msgErreur['dateArr'] = "Erreur de format sur la date ou l'heure d'arrivée";
            $isDateEnErreur = true;
        }

        // On va comparer les dates
        if (!$isDateEnErreur && $dateArrivee <= $dateDepart) {
            $msgErreur['dateArr'] = "La date-heure d'arrivée doit être postérieure à la date-heure de départ";
        }

    }

    // On vérifie qu'au moins un adulte accompagne des enfants (si présents)
    if (estPresenteEtValide('nbAdd', $values, $msgErreur)
    ) {
        if ($values['nbAdd'] === 0) {
            $msgErreur['nbAdd'] = "Un adulte est obligatoire";
        }
    }


    if (empty($msgErreur)) {
        insererVol(
            $values);
    }


    saveInSession('msgErreur', $msgErreur);
    saveInSession('values', $values);

    redirectTo(BASE_URL . '/index.php');

}
