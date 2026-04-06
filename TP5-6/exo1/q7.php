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
				<tr><th>idPersonne</th><th>nomPersonne</th></tr>
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
		echo "<tr class=\"$classHtml\"><td>$idPersonne</td><td>$nomPersonne</td></tr>";
		$ix++;
	}
	echo '	</tbody>
		  </table>';
}

//code principal
debutHtml('Liste simple - requête préparée');
$db = connexion();

// Appel de la méthode qui réalise l'insertion d'un joueur dans la base de données
// et retourne true si l'insertion s'est bien passée, ou false sinon
// Ici on ajoute un joueur nommé "Dumbo" à la base de données.
// A noter que si on exécute plusieurs fois ce code, on va ajouter plusieurs joueurs nommés "Dumbo" à la base de données
$isOkInsert = ajoutJoueur($db, "Dumbo");
if ($isOkInsert) {
	msgOK("Le joueur Dumbo a été ajouté à la base de données");
} else {
	msgKO("Problème lors de l'ajout du joueur Dumbo à la base de données");
}

// Appel de la méthode qui exécute la requête SQL et retourne le résultat de la requête
$res = selectJoueursP($db);

afficheJoueursP($res);

// si on arrive là c'est que tout s'est bien passé... 
// Libération des ressources associées au résultat de la requête
$res->closeCursor();
$db = null;
finHtml();
