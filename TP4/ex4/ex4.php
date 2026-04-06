<?php
require_once("ex4_functions.php");

$msgErreur = [];


$values = [];

// Valider que la variable $_POST existe (et n'est pas vide donc)
if (!empty($_POST) && count($_POST) > 0) {

    /*
     * Controle et validation des données reçues dans $_POST
     */

    $field = 'dateDep';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideDate($values[$field])) {
            $msgErreur[$field] = "La date de départ n'est pas valide (format JJ/MM/AAAA attendu)";
        }
    }

    $field = 'hrDep';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideHeure($values[$field])) {
            $msgErreur[$field] = "L'heure de départ n'est pas valide (format HH:MM attendu)";
        }
    }

    $field = 'dateArr';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideDate($values[$field])) {
            $msgErreur[$field] = "La date d'arrivée n'est pas valide (format JJ/MM/AAAA attendu)";
        }
    }

    $field = 'hrArr';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideHeure($values[$field])) {
            $msgErreur[$field] = "L'heure d'arrivée n'est pas valide (format HH:MM attendu)";
        }
    }

    $field = 'classe';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideSelect($values[$field], ['eco', 'ecop', 'aff', 'pclas'])) {
            $msgErreur[$field] = "La classe n'est pas valide";
        }
    }

    $field = 'nbAdd';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideEntierPos($values[$field])) {
            $msgErreur[$field] = "Le nombre d'adulte n'est pas correct (format attendu : un nombre entier)";
        } else {
            $values[$field] = intval($values[$field]);
        }
    }

    $field = 'nbEnf';
    if (isset($_POST[$field])) {
        $values[$field] = htmlspecialchars($_POST[$field]);
        if (!estValideEntierPos($values[$field])) {
            $msgErreur[$field] = "Le nombre d'enfant n'est pas correct (format attendu : un nombre entier)";
        } else {
            $values[$field] = intval($values[$field]);
        }

    }


    /*
     * Contrôle de cohérence des données
     */

    // On vérifie que la date-heure d'arrivée est bien après la date-heure de départ (si les deux sont présentes et valides)
    if (estPresenteEtValide('dateDep', $values, $msgErreur)
            && estPresenteEtValide('hrDep', $values, $msgErreur)
            && estPresenteEtValide('dateArr', $values, $msgErreur)
            && estPresenteEtValide('hrArr', $values, $msgErreur)
    ) {

        $isDateEnErreur = false;

        // Dans un premier temps, on va réellement valider les dates :
        $dateDepart = DateTime::createFromFormat('d/m/Y H:i', $values['dateDep'] . ' ' . $values['hrDep']);
        if (DateTime::getLastErrors() !== false
                && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0 )) {
            $msgErreur['dateDep'] = "Erreur de format sur la date ou l'heure de départ";
            $isDateEnErreur = true;
        }
        $dateArrivee = DateTime::createFromFormat('d/m/Y H:i', $values['dateArr'] . ' ' . $values['hrArr']);
        if (DateTime::getLastErrors() !== false
                && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0 )) {
            $msgErreur['dateArr'] = "Erreur de format sur la date ou l'heure d'arrivée";
            $isDateEnErreur = true;
        }

        // On va comparer les dates
        if (!$isDateEnErreur && $dateArrivee <= $dateDepart) {
            $msgErreur['dateArr'] = "La date-heure d'arrivée doit être après la date-heure de départ";
        }

    }

    // On vérifie qu'au moins un adulte accompagne des enfants (si présents)
    if (estPresenteEtValide('nbAdd', $values, $msgErreur)
            && estPresenteEtValide('nbEnf', $values, $msgErreur)
    ) {
        if ($values['nbEnf'] > 0 && $values['nbAdd'] === 0) {
            $msgErreur['nbAdd'] = "Un adulte est obligatoire";
        }
    }

}



?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"
    >
    <title>Trouver un vol</title>
    <link rel="stylesheet" href="ex4.css">
</head>
<body>
<div class="content">
    <section>
        <div id="d1">
            <h1>Trouver un vol</h1>
        </div>
        <div class="form-container">
            <fieldset>
                <form action="ex4.php" method="post">
                    <label for="dateDep">Date de départ:
                    </label>
                    <input type="text" id="dateDep" name="dateDep"
                           placeholder="JJ/MM/AAAA"
                           required
                    <?= afficheValue($values, 'dateDep') ?>
                    >
                    <label for="hrDep">Heure de départ:
                    </label>
                    <input type="text" id="hrDep" name="hrDep"
                           placeholder="HH:MM"
                           required
                            <?= afficheValue($values, 'hrDep') ?>
                    >

                    <?= afficheMsgErreur('dateDep', $msgErreur) ?>
                    <?= afficheMsgErreur('hrDep', $msgErreur) ?>

                    <br>

                    <label for="dateArr">Date d'arrivée:
                    </label>
                    <input type="text" id="dateArr" name="dateArr"
                           placeholder="JJ/MM/AAA" required
                            <?= afficheValue($values, 'dateArr') ?>>
                    <label for="hrArr">Heure d'arrivée:
                    </label>
                    <input type="text" id="hrArr" name="hrArr"
                           placeholder="HH:MM" required
                            <?= afficheValue($values, 'hrArr') ?>>

                    <?= afficheMsgErreur('dateArr', $msgErreur) ?>
                    <?= afficheMsgErreur('hrArr', $msgErreur) ?>

                    <br>

                    <label for="classe">Classe:</label>
                    <select name="classe" id="classe">
                        <option <?= afficheOptionSelected($values, 'classe', 'eco') ?> value="eco">Eco</option>
                        <option <?= afficheOptionSelected($values, 'classe', 'ecop') ?> value="ecop">Eco Premium
                        </option>
                        <option <?= afficheOptionSelected($values, 'classe', 'aff') ?> value="aff">Affaires</option>
                        <option <?= afficheOptionSelected($values, 'classe', 'pclas') ?> value="pclas">Première Classe
                        </option>
                    </select>
                    <?= afficheMsgErreur('classe', $msgErreur) ?>
                    <br>

                    <label for="nbAd">Nombre d'adultes:</label>
                    <input type="text" id="nbAd" name="nbAdd" required
                            <?= afficheValue($values, 'nbAdd') ?>>

                    <?= afficheMsgErreur('nbAdd', $msgErreur) ?>

                    <br>
                    <label for="nbEnf">Nombre d'enfants:</label>
                    <input type="text" id="nbEnf" name="nbEnf" required
                            <?= afficheValue($values, 'nbEnf') ?> >

                    <?= afficheMsgErreur('nbEnf', $msgErreur) ?>

                    <br>

                    <input class="bouton" type="submit" value="Rechercher un vol">

                </form>
            </fieldset>
        </div>

        <?php
if (count($msgErreur) == 0 && count($values) > 0) :
    ?>
        <div>
            <h3>Informations du vol validées :</h3>

            <ul>
                <li>Départ : <?= $values['dateDep'] ?> <?= $values['hrDep'] ?> </li>
                <li>Arrivée : <?= $values['dateArr'] ?> <?= $values['hrArr'] ?> </li>
                <li>Classe : <?= $values['classe'] ?></li>
                <li>Nombre d'adulte : <?= $values['nbAdd'] ?></li>
                <li>Nombre d'enfant : <?= $values['nbEnf'] ?></li>

            </ul>

            <h4>Bon vol !</h4>
        </div>
<?php
endif;
?>
    </section>
</div>


</body>
</html>


