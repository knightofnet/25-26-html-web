<?php

require_once('ex2q3_func.php');

// on teste d'abord si des données ont été envoyées
$a = null;
$b = null;
$c = null;
if (!empty($_POST) && count($_POST)>0) {

    // tester les chaînes reçues pour vérifier la cohérence et affecter leurs valeurs
        list($a, $b, $c, $type) = valider_valeurs_recues();

    // tout a l'air ok, on calcule
        list($delta, $r1, $r2) = calculer_racines($a, $b, $c);

}



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solutions d'une équation du second degré</title>
    <link href="ex2.css" rel="stylesheet" type="text/css" media="all">
</head>

<body>

<div id="page">

    <h1> Solutions d'une équation du second degré </h1>

    <p>
        Dans la zone "Votre équation", saisissez les valeurs des coefficients a, b et c
        en prenant soin, lorsque c'est nécessaire, de faire figurer le signe négatif. Par
        exemple, -4 x<sup>2</sup> + 3x - 5 = 0 s'écrira :
    </p>
    <p class="image"> <img src="img1.png"></p>

    <p>
        Lorsque vous avez terminé, validez en cliquant sur le bouton "envoyer". Vous pouvez
        remettre les champs à zéro en cliquant sur "réinitialiser".
    </p>
    <p class="image">
    <hr>
    </p>

    <form id="formclient" action="ex2q3.php" method="post">

        <fieldset class="bloc">

            <legend> Votre équation </legend>

            <p>
                <label>
                    <input class="nb" type="number" name="coefa" required placeholder="a" value="<?= $a ?? '' ?>">
                    x<sup>2</sup> +
                </label>

                <label>
                    <input  class="nb" type="number" name="coefb" required placeholder="b" value="<?= $b ?? '' ?>">
                    x +
                </label>

                <label>
                    <input  class="nb" type="number" name="coefc" required placeholder="c" value="<?= $c ?? '' ?>">
                </label>

                =0
            </p>

        </fieldset>


        <fieldset class="bloc">

            <legend> Types de solutions </legend>

            <p><label><input type="radio" name="typesol" value="1" <?= isset($type) && $type == 1 ? 'checked' : ''?> > Solutions réelles </label></p>
            <p><label><input type="radio" name="typesol" value="2" <?= isset($type) && $type == 2 ? 'checked' : ''?> > Solutions complexes  </label></p>

        </fieldset>

        <p class="boutons">	<input type="submit"> &nbsp; <input type="reset"> </p>
    </form>

    <?php

    if (isset($delta) && isset($r1) && isset($r2)) {
        // affichage des solutions en fonction du type
        afficher_resultat($type, $delta, $r1, $r2);
    }

    ?>

</div>

</body>
</html>
