<?php

require_once("db.inc.php"); // sur Celene, contient fonction connexion()
require_once('html.inc.php'); // sur Celene,

/**
 * Fonction qui exécute une requête SQL correcte et retourne le résultat de la requête
 * <!> elle ne fait pas d'affichage, elle se contente d'exécuter la requête et de retourner le résultat de la requête
 * 
 * @param PDO $db la connexion à la base de données
 * @return PDOStatement le résultat de la requête SQL
 */
function selectJoueurs($db)
{


	try {
		// Requête permettant d'obtenir la liste des joueurs
		// donc des enregistrements de la table 'personne' dont le champ 'typepersonne'	vaut 'joueur'
		$req = "SELECT * FROM personne WHERE typepersonne='joueur'";

		// Exécution de la requête SQL
		$res = $db->query($req);

		// Si on arrive là, c'est que la requête a été exécutée sans générer d'erreur
		// On retourne le résultat de la requête; ce n'est pas important ici. A noter
		// que ce ne sont pas les données qui sont retournées, mais un objet de type PDOStatement
		// qui permet de parcourir les données retournées par la requête.
		return $res;
	} catch (Exception $e) {
		erreurExit("Problème sur l'exécution de la requête", $e);
	}
}

/**
 * Fonction qui affiche les joueurs à partir du résultat de la requête SQL
 * <!> elle ne fait pas d'exécution de requête SQL, elle se contente d'afficher les données à partir 
 * du résultat de la requête SQL
 * 
 * @param PDOStatement $res le résultat de la requête SQL
 */
function afficheJoueurs($res)
{
	// Affichage des données retournées par la requête SQL
	// $res est un objet de type PDOStatement qui permet de parcourir les données retournées par la requête
	// grâce à la méthode fetch() qui retourne une ligne de résultat à chaque appel, ou false s'il n'y a plus de ligne à retourner	

	echo '<table>
			<thead>
				<tr><th>idPersonne</th><th>nomPersonne</th></tr>
			</thead>
			<tbody>';

	$ix = 0;
	while ($ligne = $res->fetch()) {

		$classHtml = ($ix % 2 == 0) ? '' : 'ligne2';
		echo "<tr class=\"$classHtml\"><td>" . $ligne['idpersonne'] . "</td><td>" . $ligne['nompersonne'] . "</td></tr>";

		$ix++;
	}
	echo '	</tbody>
		  </table>';
}

//code principal
debutHtml('Requête correcte');
$db = connexion();

// Appel de la méthode qui exécute la requête SQL et retourne le résultat de la requête
$res = selectJoueurs($db);

// On va réaliser ensuite l'affichage des données retournées par la requête :
afficheJoueurs($res);

// si on arrive là c'est que tout s'est bien passé... 
// Libération des ressources associées au résultat de la requête
$res->closeCursor();
$db = null;
finHtml();
