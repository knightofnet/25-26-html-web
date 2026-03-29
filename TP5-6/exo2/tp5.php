<?php

require_once("db.inc.php"); // sur Celene, contient fonction connexion()
require_once('html.inc.php'); // sur Celene,
require_once('fn_utils.inc.php');


//code principal
debutHtml('Exo2 TP5-6');
$db = connexion();

// Si des données ont été postées, c'est que le formulaire a été envoyé
if (!empty($_POST)) {

    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $typepersonne = isset($_POST['typepersonne']) ? $_POST['typepersonne'] : '';

    if (!preg_match("/^(joueur|administrateur|partenaire)$/", $typepersonne)) {
        erreurExit("Le type de personne doit être : 'joueur', 'administrateur' ou 'partenaire'");
    }

    if ($nom !== '' && $typepersonne !== '') {


        // si dans les données soumises par le formulaire, se trouve un
        // champ 'action' ave comme valeur UPD, alors on va réaliser la
        // mise à jour du nom d'un joueur.
        // SINON, on effectue l'ajout
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action == 'UPD') {
            $idDelaPersonneAMettreAJour = recupereIdEtRetourneSiValide($_POST);
            if ($idDelaPersonneAMettreAJour >= 0) {
                $isOkUpd = majPersonne($db, $idDelaPersonneAMettreAJour, $nom, $typepersonne);
                if ($isOkUpd) {
                    msgOK("Le joueur $nom a été mis à jour dans la base de données");
                } else {
                    msgKO("Problème lors de la mise à jour du joueur $nom dans la base de données");
                }
            }

        } else {

            $isOkInsert = ajoutJoueur($db, $nom, $typepersonne);
            if ($isOkInsert) {
                msgOK("Le joueur $nom a été ajouté à la base de données");
            } else {
                msgKO("Problème lors de l'ajout du joueur $nom à la base de données");
            }
        }
    } else {
        msgKO("Le nom du joueur est obligatoire pour l'ajout d'un joueur à la base de données");
    }

}

if (!empty($_GET)) {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    if ($action == 'DEL') {
        $idDelaPersonneASupprimer = recupereIdEtRetourneSiValide($_GET);
        if ($idDelaPersonneASupprimer >= 0) {
            $isSuppressionOK = supprimePersonne($db, $idDelaPersonneASupprimer);
            if ($isSuppressionOK) {
                msgOK("Personne $idDelaPersonneASupprimer supprimée");
            } else {
                msgKO("Erreur lors de la suppression du personne $idDelaPersonneASupprimer");
            }
        }
    } else if ($action == 'UPD') {

        $idDelaPersonneAMettreAJour = recupereIdEtRetourneSiValide($_GET);
        if ($idDelaPersonneAMettreAJour >= 0) {

            $infosPersonne = selectInfoUnePersonne($db, $idDelaPersonneAMettreAJour);

            formModificationHtml("Modification d'un joueur",
                $infosPersonne['nompersonne'],
                $idDelaPersonneAMettreAJour,
                $infosPersonne['typepersonne']
            );
        }
    }

}

// Appel de la méthode qui exécute la requête SQL et retourne le résultat de la requête
$res = selectJoueursP($db);

// On appelle ici une fonction, qui va réaliser l'affichage HTML du formulaire pour ajouter un jouer
formInsertionHtml();

afficheJoueursP($res);

// si on arrive là c'est que tout s'est bien passé... 
// Libération des ressources associées au résultat de la requête
$res->closeCursor();
$db = null;
finHtml();
