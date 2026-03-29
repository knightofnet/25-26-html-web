<?php
/**
	Affiche le début de la page
**/
function debutHtml($titre) { ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des données</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <p class="header-message">Gestion des données</p>
    </header>
	<nav >
		<ul id="menu">
		<li><a href="../exo1/q10.php">Joueurs</a></li>
		<li><a href="tp5.php">Tous</a></li>

		</ul>
	</nav>
    <div class="content">

    <section >
		<h1><?php if (isset($titre)) echo $titre;?></h1>

<?php } ?>

<?php
/**
	Affiche la fin de la page
**/
function finHtml() { ?>
	</section>

    </div>

    <footer>
        <p class="footer-message">© 2025 Mini Site Web Statique. Tous droits réservés.</p>
    </footer>

</body>
</html>
<?php } ?>

<?php
/**
	Affiche un message d'erreur en rouge
	et arrêt du programme
**/
function erreurExit($msg,$e=null) {
	echo "<span class=\"rouge\">";
	echo $msg;
	if (isset($e)) {
		echo " ";
		print_r($e);
	}
	echo "</span><br>";
	finHtml();
	exit;
}
/**
	Affiche un message en vert
**/
function msgOK($msg=null) {
	echo "<span class=\"vert gras\">";
	echo "$msg <br><br>";
	echo "</span>";
}
/**
	Affiche un message d'erreur en rouge
**/
function msgKO($msg=null) {
	echo "<span class=\"rouge\">";
	echo "$msg <br><br>";
	echo "</span>";
}
?>


<?php
/**
	Formulaire d'ajout d'un joueur
	q8
**/
function formInsertionHtml() { ?>
<form class="form-container" method="post" action="tp5.php">
    <br>
	<fieldset> <legend> Saisie d'une personne </legend>
	<br>
	<label for="nom">Nom : </label>
    <input type="text" id="nom" name="nom">
    </p>

    <label for="type">Type : </label>
    <input type="text" id="type" name="typepersonne" value="joueur">
    </p>

    <br/>
    <input type="submit">
	</fieldset>
</form>
<?php } ?>

<?php
/**
	Formulaire de modification d'une personne
	q10=q8 avec les parametre $id et $nom en plus
	si $nom ou $id n'est pas passé leur valeur par défaut est null
**/
//
function formModificationHtml($titre, $nom=null, $id=null, $typepersonne=null) { ?>
<form class="form-container" method="post" action="tp5.php">
    <br>
	<fieldset> <legend><?php echo $titre;?></legend>
	<br>
	<label for="nom">Nom : </label>
    <input type="text" name="nom"
	<?php if (isset($nom)) echo 'value="'.$nom.'"';?>
	>

    <label for="type">Type : </label>
    <input type="text" id="type" name="typepersonne"
    <?= isset($typepersonne) ? 'value="'.$typepersonne.'"' : '' ?>
    >
    </p>

	<?php
	if (isset($id)) {
	?>
	<input type="hidden" name="id" <?php echo 'value="'.$id.'"';?> >
	<input type="hidden" name="action" value="UPD">
	<?php
	}
	?>
    </p>
    <br/>
    <input type="submit">
	</fieldset>
</form>
<?php } ?>

<?php //tp12 complet
function afficheBoutonAjout() { ?>
	<p>
		<input type="button" value="Ajouter" title="Ajouter une nouvelle personne" onclick="window.location.href='tp12.php?action=INS';">
	</p>
<?php } ?>
<?php //tp12 complet
// si $nom ou $id n'est pas passé leur valeur par défaut est null
function formInsertionModificationHtml($titre, $nom=null, $id=null) { ?>
<form method="post" action="tp12.php">
    <br>
	<fieldset> <legend><?php echo $titre;?></legend>
	<br>
	<label for="nom">Nom : </label>
    <input type="text" name="nom"
	<?php if (isset($nom)) echo 'value="'.$nom.'"';?>
	>
	<?php
	if (isset($id)) {
	?>
	<input type="hidden" name="id" <?php echo 'value="'.$id.'"';?> >
	<input type="hidden" name="action" value="UPD">
	<?php
	}
	else {
	?>
	<input type="hidden" name="action" value="INS">
	<?php
	}
	?>
    </p>
    <br/>
    <input type="submit">
	</fieldset>
</form>
<?php }

function afficheJoueursP($res)
{
    echo '<table>
			<thead>
				<tr>
				    <th>ID</th>
				    <th>NOM</th>
				    <th>TYPE</th>
				    
				    <th colspan="2"></th>
                </tr>
			</thead>
			<tbody>';


    $idPersonne = null;
    $nomPersonne = null;
    $typePersonne = null;

    // On effectue le lien entre les champs de la table 'personne' et les variables $idPersonne et $nomPersonne grâce à la méthode bindColumn() de l'objet PDOStatement
    $res->bindColumn('idpersonne', $idPersonne);
    $res->bindColumn('nompersonne', $nomPersonne);
    $res->bindColumn('typepersonne', $typePersonne);

    $ix = 0;
    // Parcours des résultats et affichage
    while ($res->fetch(PDO::FETCH_BOUND)) {
        $classHtml = ($ix % 2 == 0) ? '' : 'ligne2';
        echo "<tr class=\"$classHtml\">";
        echo "<td>$idPersonne</td><td>$nomPersonne</td>";

        echo "<td>$typePersonne</td>";

        echo "<td><a href=\"tp5.php?action=DEL&id=$idPersonne\">DEL</a></td>";

        echo "<td><a href=\"tp5.php?action=UPD&id=$idPersonne\">UPD</a></td>";

        echo "</tr>";
        $ix++;
    }
    echo '	</tbody>
		  </table>';
}

?>
