<?php

require_once("db.inc.php"); // sur Celene, contient fonction connexion()
require_once('html.inc.php'); // sur Celene,

function selectCorrect($db)
{


	try {
		// Requête correcte : la table existe
		// --> C'est l'objectif de cette méthode : exécuter une requête correcte
		// 	   afin de tester la gestion des erreurs
		$req = "SELECT * FROM console";

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
//code principal
debutHtml('Requête correcte');
$db = connexion();
$res = selectCorrect($db);
// si on arrive là c'est que tout s'est bien passé... 
echo "Execution de la requête réussie\n<br>";
$res->closeCursor();
$db = null;
finHtml();
