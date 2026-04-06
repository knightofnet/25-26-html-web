<?php

require_once("ex3_functions.php");

$msgErreur = [];


// Contrôle de la page qui appelle le PHP
if (!strpos($_SERVER['HTTP_REFERER'], 'ex3.php')) {
    $msgErreur['global'] = "L'origine des données n'est pas certifiée";
}

// Valider que la variable $_POST existe (et n'est pas vide donc)
$values = [];

if (!empty($_POST) && count($_POST) > 0) {


    if (isset($_POST['ref'])) {
        $values['ref'] = htmlspecialchars($_POST['ref']);
        if (!estValideMajuscule($values['ref'])) {
            $msgErreur['ref'] = "La référence doit être composée de majuscules uniquement";
        }
    }

    if (isset($_POST['des'])) {
        $values['des'] = htmlspecialchars($_POST['des']);
        if (!estValideMajMinChiffres($values['des'])) {
            $msgErreur['des'] = "La designation doit être composée de majuscules, minuscules ou chiffres uniquement";
        }
    }

    if (isset($_POST['dateEntree'])) {
        $values['dateEntree'] = htmlspecialchars($_POST['dateEntree']);
        if (!estValideDate($values['dateEntree'])) {
            $msgErreur['dateEntree'] = "La date d'entrée doit être au format cc/cc/cccc";
        }
    }


    if (isset($_POST['prix'])) {
        $values['prix'] = htmlspecialchars($_POST['prix']);
        if (!estValideNombrePos($values['prix'])) {
            $msgErreur['prix'] = "Le prix doit être un nombre positif";

        }
    }

    if (isset($_POST['fourn'])) {
        $values['fourn'] = htmlspecialchars($_POST['fourn']);
        if (!estValideSelect($values['fourn'], ['hp', 'cisco', 'dell', 'verb', 'asus'])) {
            $msgErreur['fourn'] = "Le fournisseur doit être un des suivants : HP, CISCO, Dell, Verbatim ou Asus";
        }
    }


    if (isset($_POST['qtestk'])) {
        $values['qtestk'] = htmlspecialchars($_POST['qtestk']);
        if (!estValideEntierPos($values['qtestk'])) {
            $msgErreur['qtestk'] = "La quantité doit etre un nombre entier positif";

        }
    }


    if (isset($_POST['allee'])) {
        $values['allee'] = htmlspecialchars($_POST['allee']);
        if (!estValideMajChiffres($values['allee'])) {
            $msgErreur['allee'] = "L'allée doit etre composée de majuscules et de chiffres uniquement";

        }
    }

    if (isset($_POST['etag'])) {
        $values['etag'] = htmlspecialchars($_POST['etag']);
        if (!estValideMajChiffres($values['etag'])) {
            $msgErreur['etag'] = "L'étage doit etre composée de majuscules et de chiffres uniquement";

        }
    }

    if (isset($_POST['case'])) {
        $values['case'] = htmlspecialchars($_POST['case']);
        if (!estValideMajChiffres($values['case'])) {
            $msgErreur['case'] = "La case doit etre composée de majuscules et de chiffres uniquement";

        }
    }

}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"
    >
    <title>Ajout de produit</title>
    <link rel="stylesheet" href="ex3.css">
</head>
<body>
<div class="content">
    <section>
        <div id="d1">
            <h1>Ajout d'un nouvel article en stock</h1>
        </div>
        <div class="form-container">
            <form action="ex3.php" method="post">
                <?= afficheMsgErreur($msgErreur, 'ref') ?>
                <label for="ref">Référence:</label>
                <input type="text" id="ref" name="ref" <?= afficheValue($values, 'ref') ?>
                       placeholder="Référence article en MAJUSCULES"
                       required pattern="[A-Z]+">
                <br>

                <?= afficheMsgErreur($msgErreur, 'des') ?>
                <label for="des">Désignation:</label>
                <input type="text" id="des" name="des" <?= afficheValue($values, 'des') ?>
                       placeholder="Désignation"
                       required pattern="[A-Za-z0-9]+">
                <br>

                <?= afficheMsgErreur($msgErreur, 'dateEntree') ?>
                <label for="dateEntree">Date d'entrée en stock:
                </label>
                <input type="text" id="dateEntree" <?= afficheValue($values, 'dateEntree') ?> name="dateEntree"
                       placeholder="__/__/____"
                       required pattern="\d{2}/\d{2}/\d{4}">
                <br>

                <?= afficheMsgErreur($msgErreur, 'fourn') ?>
                <label for="fourn">Fournisseur:</label>
                <select name="fourn" id="fourn">
                    <option <?= afficheOptionSelected($values, 'fourn', 'hp') ?> value="hp">HP</option>
                    <option <?= afficheOptionSelected($values, 'fourn', 'cisco') ?> value="cisco">CISCO</option>
                    <option <?= afficheOptionSelected($values, 'fourn', 'dell') ?> value="dell">Dell</option>
                    <option <?= afficheOptionSelected($values, 'fourn', 'verb') ?> value="verb">Verbatim</option>
                    <option <?= afficheOptionSelected($values, 'fourn', 'asus') ?> value="asus">Asus</option>
                </select><br>

                <?= afficheMsgErreur($msgErreur, 'prix') ?>
                <label for="prix">Prix:</label>
                <input type="text" id="prix" name="prix" placeholder="Prix" <?= afficheValue($values, 'prix') ?>
                       required pattern="[0-9]+(\.[0-9]+)?">
                <br>

                <?= afficheMsgErreur($msgErreur, 'qtestk') ?>
                <label for="qtestk">Quantité en stock:</label>
                <input type="text" id="qtestk" name="qtestk"
                       placeholder="Quantité" <?= afficheValue($values, 'qtestk') ?>
                       required pattern="[0-9]+">
                ><br>

                <?= afficheMsgErreur($msgErreur, 'allee') ?>
                <?= afficheMsgErreur($msgErreur, 'etag') ?>
                <?= afficheMsgErreur($msgErreur, 'case') ?>

                <fieldset>
                    <legend>Rangement</legend>

                    <label for="allee">Allée:</label>
                    <input type="text" id="allee" name="allee" <?= afficheValue($values, 'allee') ?> size=5
                           required pattern="[A-Z0-9]+">
                    >


                    <label for="etag"> Etagère:</label>
                    <input type="text" id="etag" name="etag" <?= afficheValue($values, 'etag') ?> size=5
                           required pattern="[A-Z0-9]+">


                    <label for="case">Casier:</label>
                    <input type="text" id="case" name="case" <?= afficheValue($values, 'case') ?> size=5
                           required pattern="[A-Z0-9]+">
                </fieldset>
                <br>

                <input class="bouton" type="submit" value="Ajouter au stock">
                <input class="bouton" type="reset" name="reset" value="Annuler"/>

            </form>
        </div>

        <?php

        if (count($msgErreur) == 0 && count($values) > 0) :
            ?>

            <h3>Données reçues et validées :</h3>

            <ul>
                <li>Référence : <?= $values['ref'] ?></li>
                <li>Désignation : <?= $values['des'] ?></li>
                <li>Date d'entrée en stock : <?= $values['dateEntree'] ?></li>
                <li>Fournisseur : <?= $values['fourn'] ?></li>
                <li>Prix : <?= $values['prix'] ?> €</li>
                <li>Quantité en stock : <?= $values['qtestk'] ?></li>
                <li>Allée : <?= $values['allee'] ?></li>
                <li>Etagère : <?= $values['etag'] ?></li>
                <li>Casier : <?= $values['case'] ?></li>
            </ul>

        <?php
        endif;


        ?>

    </section>
</div>


</body>
</html>





