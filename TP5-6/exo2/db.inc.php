<?php
require_once('html.inc.php');
/**
 * Connexion au serveur de bases de données.
 * Il est nécessaire d'ajuster les paramètres en fonction de votre configuration
 * Si la connexion échoue, un message est affiché et le traitement sur le serveur
 * s'arrête.
 **/
function connexion()
{

    try {

        $nomDeLaBaseDeDonnees = 'JeuxVideos';
        $utilisateurMysql = 'root';
        $motDePasseMysql = '';

        $dsn = 'mysql:host=localhost;charset=utf8;port=3306;dbname=' . $nomDeLaBaseDeDonnees;
        $db = new PDO($dsn, $utilisateurMysql, $motDePasseMysql, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        erreurExit("Erreur d'accès à la base de données - FIN");
    }

    return $db;
}


function selectJoueursP($db, $type = '')
{

    try {
        // Etape 1 : on écrit la requête SQL avec un paramètre (le point d'interrogation) à la place de la valeur du paramètre
        // ----
        // Requête permettant d'obtenir la liste des joueurs
        // donc des enregistrements de la table 'personne' dont le champ 'typepersonne'	vaut $type
        if (empty($type)) {
            $req = "SELECT * FROM personne";
        } else {
            $req = "SELECT * FROM personne WHERE typepersonne = ? ";
        }

        // Etape 2 : Préparation de la requête SQL
        // ----
        // La préparation de la requête permet de séparer la requête SQL de ses paramètres,
        // ce qui permet d'éviter les injections SQL
        $stmt = $db->prepare($req);

        if (!empty($type)) {

            // Etape 3 : On fournit la valeur du paramètre
            // ----
            // On fournit la valeur du paramètre (le type de personne recherché), qui vaut 'joueur' dans notre cas
            // Lors de l'exécution de la requête, le moteur de base de données va remplacer le point d'interrogation
            //par la valeur du paramètre fournie
            $stmt->bindParam(1, $type, PDO::PARAM_STR);
        }

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
 * Récupère les informations d'une personne à partir de son ID
 *
 * @param $db
 * @param $id
 * @return array|false
 */
function selectInfoUnePersonne($db, $id)
{
    try {

        $req = "SELECT * FROM personne WHERE idpersonne = ?";

        $stmt = $db->prepare($req);

        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête", $e);
    }
}


function ajoutJoueur($db, $nom, $typepersonne)
{
    try {
        $req = "INSERT INTO personne (nompersonne, typepersonne) VALUES (?, ?)";
        $stmt = $db->prepare($req);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $typepersonne, PDO::PARAM_STR);
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


function majPersonne($db, $id, $nom, $typepersonne)
{
    try {
        $req = "UPDATE personne SET nompersonne = ?, typepersonne = ? WHERE idpersonne = ?";
        $stmt = $db->prepare($req);
        $stmt->bindParam(1, $nom, PDO::PARAM_STR);
        $stmt->bindParam(2, $typepersonne, PDO::PARAM_STR);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);
        $isOkUpdate = $stmt->execute();
        return $isOkUpdate;
    } catch (Exception $e) {
        erreurExit("Problème sur l'exécution de la requête de mise à jour d'une personne", $e);
    }

}
