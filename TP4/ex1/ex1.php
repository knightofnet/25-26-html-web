<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institut du bijou</title>
    <link rel="stylesheet" href="ex1.css">
</head>
<body>
    <header>
        <p class="header-message"></p>
    </header>
        <div class="content">
        <section id="s1">
		    <div id="d1">
			<img class="image" src="bijoux.jpg" alt="Bague représentant la formation en bijouterie">
			<h1>Institut du bijou</h1>
			</div>
			<div id="d2">
			<h2>Candidatez à notre formation...</h2>

<?php

include("mesFonctions.php");

$statut=false;
$ok=true;
if (isset($_POST) && count($_POST)==7){
	$statut = true;

	// On teste l'origine/le fichier qui a transmis les données du formulaire
	if (strpos($_SERVER['HTTP_REFERER'],'ex1.php')== false) $statut=false;

	// On teste que la variable $_POST n'est pas vide
	if (empty($_POST)) $statut=false;

	// On teste que la variable $_POST contient bien la clé 'nom'
	if (!isset($_POST['nom'])) $statut=false;
	else {

		// On affecte à $nom la valeur de $_POST['nom']
		$nom=$_POST['nom'];

		// On teste que la valeur de $nom respecte bien les conditions de validation demandées : ici que tous les caractères soient des majuscules.		
		if (!estValideMajuscule($nom)) {
			$statut=false;
			$ok=false;
		}

		$prenom = $_POST['prenom'];
		if (!preg_match('/^[A-Za-z]+$/',$prenom)) {
			$statut=false;
			$ok=false;
		}	

		$dateNaiss = $_POST['dateNaiss'];
		if (!estValideDate($dateNaiss)) {
			$statut=false;
			$ok=false;
		}		
		
		$tel = $_POST['tel'];
		if (!estValideNumTel($tel)) {
			$statut=false;
			$ok=false;
		}	

		$email = $_POST['email'];
		if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
			$statut=false;		
			$ok=false;
		}



	}

}
if (!$statut) { // on saisit ou resaisit le formulaire...
	if (!$ok) echo "<span class=rouge> Erreur(s) dans la saisie des champs </span> \n<br><br>";
?>
			<div class="form-container">
                <form action="ex1.php" method="post">
					<label for="civil">Civilité:</label>
					<select name="civil" id="civil">
						<option value="M">M.</option>
						<option value="Mme">Mme</option>
					</select><br>               
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" placeholder="NOM en majuscules" <?php if (isset($nom)) echo ' value="' . $nom . '" ' ?>><br>
                    <label for="nom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom"  placeholder="Prénom" <?php if (isset($prenom)) echo ' value="' . $prenom . '" ' ?>><br>
                    <label for="dateNaiss">Date de naissance:</label>
                    <input type="text" id="dateNaiss" name="dateNaiss" placeholder="__/__/____" <?php if (isset($dateNaiss)) echo ' value="' . $dateNaiss . '" ' ?>><br>
					<label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Email" <?php if (isset($email)) echo ' value="' . $email . '" ' ?>><br>
					<label for="tel">N° Téléphone:</label>
                    <input type="text" id="tel" name="tel" placeholder="N° Téléphone" <?php if (isset($tel)) echo ' value="' . $tel . '" ' ?>><br>
                    <label for="interet">Interet pour la formation:</label><br>
                    <textarea id="interet" name="interet" rows="4" ><?php if (isset($interet)) echo $interet ?></textarea><br><br>
					
                    <input type="submit" value="Valider">
					
                </form>
			</div>
           
<?php
}
else {
	?>
	<span class="gras"> Formulaire validé, votre candidature va être prise en compte...</span>
	<?php
	}
?>
	</div>
	</section>

    </div>
    
    <footer>
        <p class="footer-message">© 2025 TPs PHP. Tous droits réservés.</p>
    </footer>

</body>
</html>
