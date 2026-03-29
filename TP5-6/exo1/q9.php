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

/**
 * Fonction responsable de la suppresion de la personne dans la base de données
 *
 * @param $db PDO le handle de connexion c'est à dire la connexion
 * @param $id int l'identifiant de la personne à supprimer
 * @return bool true si la suppression s'est bien passée, ou false sinon
 */
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
				<tr><th>idPersonne</th><th>nomPersonne</th><th></th></tr>
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

        // on ajoute la 3eme colonne demandée dans la quesiton 9 : elle
        // affiche un lien DEL, qui renvoie vers la même URL, mais avec deux paramètres
        // dans l'URL : action = DEL, et id = l'identifiant de la personne que l'on souhaite
        // supprimer de la base de données.
        echo "<td><a href=\"q9.php?action=DEL&id=$idPersonne\"> DEL </a></td>";

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
        $isOkInsert = ajoutJoueur($db, $nom);
        if ($isOkInsert) {
            msgOK("Le joueur $nom a été ajouté à la base de données");
        } else {
            msgKO("Problème lors de l'ajout du joueur $nom à la base de données");
        }
    } else {
        msgKO("Le nom du joueur est obligatoire pour l'ajout d'un joueur à la base de données");
    }

}

// Si des données ont été passées dans l'URL, après le caractère ? (par exemple q9.php?action=DEL...)
if (!empty($_GET)) {

    // On récupère l'action, si dans l'URL on a un paramètre action (par exemple q9.php?action=DEL),
    // sinon on met une chaîne vide.
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    // Si le contenu de $action est DEL
    if ($action == 'DEL') {
        // Alors on va récupérer l'identifiant de la personne à supprimer, qui doit être passé
        // dans l'URL avec le paramètre id (par exemple q9.php?action=DEL&id=3), sinon on met
        // une chaine vide
        $idDelaPersonneASupprimer = isset($_GET['id']) ? $_GET['id'] : '';

        // Si l'identifiant de la personne à supprimer n'est pas une chaine vide,
        // et que c'est un nombre (on vérifie que c'est un nombre avec une expression régulière),
        // alors on peut supprimer la personne de la base de données
        if ($idDelaPersonneASupprimer !== ''
            && preg_match("/^[0-9]+$/", $idDelaPersonneASupprimer)
        ) {
            $isSuppressionOK = supprimePersonne($db, $idDelaPersonneASupprimer);
            if ($isSuppressionOK) {
                msgOK("Personne $idDelaPersonneASupprimer supprimée");
            } else {
                msgKO("Erreur lors de la suppression du personne $idDelaPersonneASupprimer");
            }
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
