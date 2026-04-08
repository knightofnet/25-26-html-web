<?php

require_once(PROJECT_ROOT . '/php/funcs/base_func.php');
require_once(PROJECT_ROOT . '/php/funcs/db_func.php');

function insererVol(array $values): bool
{

    $connexion = creerConnexion();
    try {

        $sql = "INSERT INTO vol (date_dep, date_arr, classe, nb_adultes, nb_enfants) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);

        // On convertie le format de date et heure du formulaire en format compatible avec la base de données
        $dateDepart = DateTime::createFromFormat('d/m/Y H:i', $values['dateDep'] . ' ' . $values['hrDep'])->format('Y-m-d H:i:s');
        $dateArrivee = DateTime::createFromFormat('d/m/Y H:i', $values['dateArr'] . ' ' . $values['hrArr'])->format('Y-m-d H:i:s');

        $stmt->bindParam(1, $dateDepart, PDO::PARAM_STR);
        $stmt->bindParam(2, $dateArrivee, PDO::PARAM_STR);
        $stmt->bindParam(3, $values['classe'], PDO::PARAM_STR);
        $stmt->bindParam(4, $values['nbAdd'], PDO::PARAM_INT);
        $stmt->bindParam(5, $values['nbEnf'], PDO::PARAM_INT);

        return $stmt->execute();

    } catch (Exception $e) {
        erreurExit("Erreur lors de l'insertion d'un nouveau vol", $e);
        return false;
    }

}

function recupererVolsEnregistres()
{
    return genericSelectVolsEnregistres("SELECT * FROM vol");
}


function recupererDernierVolEnregistre()
{
    $dernierVol = genericSelectVolsEnregistres("SELECT * FROM vol ORDER BY id DESC LIMIT 1");
    if (empty($dernierVol)) {
        return null;
    }
    return $dernierVol[0];
}

function resetVolsEnregistres()
{
    $connexion = creerConnexion();

    try {

        $sql = "DELETE FROM vol";

        $stmt = $connexion->prepare($sql);
        return $stmt->execute();

    } catch (Exception $e) {
        erreurExit("Erreur lors de la suppression des enregistrements", $e);
        return false;
    }
}


function genericSelectVolsEnregistres($sql)
{

    $volsEnregistres = [];

    $connexion = creerConnexion();

    try {


        $stmt = $connexion->prepare($sql);
        $stmt->execute();

        while ($volFromBdd = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dateDepart = new DateTime($volFromBdd['date_dep']);
            $dateArrivee = new DateTime($volFromBdd['date_arr']);

            $volForm = [];
            $volForm['dateDep'] = $dateDepart->format('d/m/Y');
            $volForm['dateArr'] = $dateArrivee->format('d/m/Y');
            $volForm['hrDep'] = $dateDepart->format('H:i');
            $volForm['hrArr'] = $dateArrivee->format('H:i');
            $volForm['nbAdd'] = $volFromBdd['nb_adultes'];
            $volForm['nbEnf'] = $volFromBdd['nb_enfants'];
            $volForm['classe'] = $volFromBdd['classe'];

            $volsEnregistres[] = $volForm;
        }

        return $volsEnregistres;
    } catch (Exception $e) {
        erreurExit("Erreur lors de la récupération de l'historique vols", $e);
        return false;
    }

}