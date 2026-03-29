<?php

require_once("db.inc.php"); // sur Celene, contient fonction connexion()
require_once('html.inc.php'); // sur Celene,

/**
 * Fonction qui prépare et exécute une requête SQL correcte et retourne le résultat de la requête
 * <!> elle ne fait pas d'affichage, elle se contente d'exécuter la requête et de retourner le résultat de la requête
 *
 * @param PDO $db la connexion à la base de données
 * @param string $type le type de personne recherché (joueur, developpeur, etc.). Par défaut, la valeur du type de personne recherché est 'joueur'
 * @return PDOStatement le résultat de la requête SQL
 */
function selectJoueursP($db, $type = 'joueur')
{

    try {
        // Etape 1 : on écrit la requête SQL avec un paramètre (le point d'interrogation) à la place de la valeur du paramètre
        // ----
        // Requête permettant d'obtenir la liste des joueurs
        // donc des enregistrements de la table 'personne' dont le champ 'typepersonne'	vaut $type
        $req = "SELECT * FROM personne WHERE typepersonne = ? ";

        // Etape 2 : Préparation de la requête SQL
        // ----
        // La préparation de la requête permet de séparer la requête SQL de ses paramètres,
        // ce qui permet d'éviter les injections SQL
        $stmt = $db->prepare($req);

        // Etape 3 : On fournit la valeur du paramètre
        // ----
        // On fournit la valeur du paramètre (le type de personne recherché), qui vaut 'joueur' dans notre cas
        // Lors de l'exécution de la requête, le moteur de base de données va remplacer le point d'interrogation
        //par la valeur du paramètre fournie
        $stmt->bindParam(1, $type, PDO::PARAM_STR);

        // Etape 4 : Exécution de la requête SQL
        // ----
        // Exécution de la requête SQL préparée avec le paramètre fourni
        $stmt->execute();

        return $stmt;
    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête", $e);
    }
}

/**
 * Fonction qui réalise l'insertion d'un joueur dans la base de données,
 * et retourne true si l'insertion s'est bien passée, ou false sinon.
 *
 * @param PDO $db la connexion à la base de données
 * @param string $nom le nom du joueur à ajouter
 * @return bool true si l'insertion s'est bien passée, ou false sinon
 *
 */
function ajoutJoueur($db, $nom)
{
    try {
        $req = "INSERT INTO personne (nompersonne, typepersonne) VALUES (?, 'joueur')";
        $stmt = $db->prepare($req);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $isOkInsert = $stmt->execute();

        return $isOkInsert;
    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête d'insertion d'un joueur", $e);
    }
}

function supprimePersonne($db, $id)
{
    try {
        $req = "DELETE FROM personne WHERE idpersonne = ?";
        $stmt = $db->prepare($req);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $isOkDelete = $stmt->execute();
        return $isOkDelete;
    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête de suppression d'une personne", $e);
    }
}

/**
 * Fonction responsable de la mise à jour du nom d'une personne dans la base de données,
 * en fonction de son id
 *
 * @param $db PDO le handle de connexion c'est à dire la connexion
 * @param $id int l'identifiant de la personne à modifier
 * @param $nom string le nouveau nom de la personne à modifier
 * @return bool true si la mise à jour s'est bien passée, faux sinon
 */
function majPersonne($db, $id, $nom)
{
    try {
        $req = "UPDATE personne SET nompersonne = ? WHERE idpersonne = ?";
        $stmt = $db->prepare($req);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        $isOkUpdate = $stmt->execute();
        return $isOkUpdate;
    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête de mise à jour d'une personne", $e);
    }

}

/**
 * Récupère l'identifiant à partir d'un tableau source (par exemple $_GET ou $_POST),
 * et retourne l'identifiant si celui-ci est valide, ou -1 sinon
 *
 * @param array $source un tableau source (par exemple $_GET ou $_POST) à partir duquel récupérer l'identifiant
 * @param $idKey string la clé de l'identifiant dans le tableau source (par défaut, la valeur de la clé de l'identifiant est 'id')
 * @return int la valeur de l'identifiant, ou -1 si l'identifiant n'est pas présent dans le tableau source, ou si l'identifiant n'est pas un entier positif
 */
function recupereIdEtRetourneSiValide(array $source, $idKey = 'id')
{
    $idStr = isset($source[$idKey]) ? $source[$idKey] : '';

    if (empty($idStr)) {
        return -1;
    }

    if (!preg_match("/^[0-9]+$/", $idStr)) {
        return -1;
    }

    return intval($idStr);
}

/**
 * Fonction qui affiche les joueurs à partir du résultat de la requête SQL.
 * La méthode de récupération des données est différente de la méthode afficheJoueurs() :
 * ici, on utilise la méthode bindColumn() de l'objet PDOStatement pour faire le lien
 * entre les champs de la table 'personne' et des variables PHP,
 * et la méthode fetch(PDO::FETCH_BOUND) pour récupérer les données.
 *
 * <!> elle ne fait pas d'exécution de requête SQL, elle se contente d'afficher les données à partir du résultat de la requête SQL
 *
 * @param PDOStatement $res le résultat de la requête SQL
 */
function afficheJoueursP($res)
{
    echo '<table>
			<thead>
				<tr><th>idPersonne</th><th>nomPersonne</th><th></th><th></th></tr>
			</thead>
			<tbody>';


    $idPersonne = null;
    $nomPersonne = null;

    // On effectue le lien entre les champs de la table 'personne' et les variables $idPersonne et $nomPersonne grâce à la méthode bindColumn() de l'objet PDOStatement
    $res->bindColumn('idpersonne', $idPersonne);
    $res->bindColumn('nompersonne', $nomPersonne);

    $ix = 0;
    // Parcours des résultats et affichage
    while ($res->fetch(PDO::FETCH_BOUND)) {
        $classHtml = ($ix % 2 == 0) ? '' : 'ligne2';
        echo "<tr class=\"$classHtml\">";
        echo "<td>$idPersonne</td><td>$nomPersonne</td>";

        echo "<td><a href=\"q10.php?action=DEL&id=$idPersonne\">DEL</a></td>";

        echo "<td><a href=\"q10.php?action=UPD&id=$idPersonne\">UPD</a></td>";

        echo "</tr>";
        $ix++;
    }
    echo '	</tbody>
		  </table>';
}

//code principal
debutHtml('Liste simple - requête préparée');
$db = connexion();

// Si des données ont été postées, c'est que le formulaire a été envoyé
if (!empty($_POST)) {

    if (isset($_POST['nom'])) {
        $nom = $_POST['nom'];

        // si dans les données soumises par le formulaire, se trouve un
        // champ 'action' ave comme valeur UPD, alors on va réaliser la
        // mise à jour du nom d'un joueur.
        // SINON, on effectue l'ajout
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        if ($action == 'UPD') {
            $idDelaPersonneAMettreAJour = recupereIdEtRetourneSiValide($_POST);
            if ($idDelaPersonneAMettreAJour >= 0) {
                $isOkUpd = majPersonne($db, $idDelaPersonneAMettreAJour, $nom);
                if ($isOkUpd) {
                    msgOK("Le joueur $nom a été mis à jour dans la base de données");
                } else {
                    msgKO("Problème lors de la mise à jour du joueur $nom dans la base de données");
                }
            }

        } else {

            $isOkInsert = ajoutJoueur($db, $nom);
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

        // On récupère l'ID avec la fonction recupereIdEtRetourneSiValide(),
        // qui va retourner l'ID si celui-ci est valide, ou -1 sinon
        $idDelaPersonneAMettreAJour = recupereIdEtRetourneSiValide($_GET);
        if ($idDelaPersonneAMettreAJour >= 0) {

            // On affiche le formulaire de modification d'un joueur,
            // en passant l'ID de la personne à modifier au formulaire,
            // pour que le formulaire puisse faire le lien entre la personne
            // à modifier et les données du formulaire
            formModificationHtml("Modification d'un joueur", null, $idDelaPersonneAMettreAJour);
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
