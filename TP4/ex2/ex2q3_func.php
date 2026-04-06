<?php
function valider_valeurs_recues()
{

    // Valider l'origine des données
    // On teste l'origine/le fichier qui a transmis les données du formulaire
    if (!strpos($_SERVER['HTTP_REFERER'], 'ex2q3.php')) {
        echo "L'origine des données n'est pas certifiée";
        die();
    }

    // Valider que la variable $_POST existe (et n'est pas vide donc)
    if (empty($_POST)) {
        echo "Aucune donnée n'a été reçue";
        die();
    }

    // Tester et valider chacune des données (coefa, coefb, coefc, et typesol)
    if (!isset($_POST['coefa'])) {
        echo "Aucune coefficient a n'a été transmis";
        die();
    }
    $coefaChaine = htmlspecialchars($_POST['coefa']);
    if (!valider_nombre($coefaChaine)) {
        echo "La coefficient 'a' a une valeur incorrecte";
        die();
    }
    $coefa = intval($coefaChaine);


    if (!isset($_POST['coefb'])) {
        echo "Aucune coefficient ab n'a été transmis";
        die();
    }
    $coefbChaine = htmlspecialchars($_POST['coefb']);
    if (!valider_nombre($coefbChaine)) {
        echo "La coefficient 'b' a une valeur incorrecte";
        die();
    }
    $coefb = intval($coefbChaine);


    if (!isset($_POST['coefc'])) {
        echo "Aucune coefficient c n'a été transmis";
        die();
    }
    $coefcChaine = htmlspecialchars($_POST['coefc']);
    if (!valider_nombre($coefcChaine)) {
        echo "La coefficient 'c' a une valeur incorrecte";
        die();
    }
    $coefc = intval($coefcChaine);


    if (!isset($_POST['typesol'])) {
        echo "Aucun type n'a été transmis";
        die();
    }
    $typesolChaine = htmlspecialchars($_POST['typesol']);
    if (!valider_type($typesolChaine)) {
        echo "La type n'est pas incorrecte";
        die();
    }
    $type = intval($typesolChaine);

    return array($coefa, $coefb, $coefc, $type);
}

function valider_nombre($n)
{
    return preg_match('/^-?\d+$/', $n);
}

function valider_type($t)
{
    return preg_match('/^[1-2]$/', $t);
}

function calculer_racines($a, $b, $c)
{
    $delta = $b * $b - 4 * $a * $c;
    $deuxa = 2 * $a;
    if ($delta < 0)
        $raciD = sqrt(-$delta);
    else
        $raciD = sqrt($delta);

    $A = -$b / $deuxa;
    $B = $raciD / $deuxa;

    if ($delta < 0) {
        $r1 = "( " . $A . " ) - ( " . $B . " ) i";
        $r2 = "( " . $A . " ) + ( " . $B . " ) i";
    } else {
        if ($delta == 0) {
            $r1 = "( " . $A . " )";
            $r2 = $r1;
        } else {
            $r1 = "( " . ($A + $B) . " )";
            $r2 = "( " . ($A - $B) . " )";
        }
    }

    // retour
    return array($delta, $r1, $r2);
}

function afficher_resultat($type, $delta, $r1, $r2)
{
    // variables pour la structure de la page
    $before = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
    $before .= '<title>Solutions de l\'équation</title></head><body>';
    $after = '</body></html>';

    echo $before;
    echo '<p class="image"><hr></p>';
    echo '<fieldset class="bloc"><legend> Solutions </legend>';

    switch ($type) {
        case 1 : // reels
            if ($delta < 0)
                echo "Pas de solution réelles";
            else
                if ($delta == 0)
                    echo "Une solution : " . $r1;
                else
                    echo "Deux solutions : " . $r1 . " et " . $r2;
            break;
        case 2 : // complexe
            if ($delta < 0)
                echo "Deux solutions complexes : " . $r1 . " et " . $r2;
            else
                if ($delta == 0)
                    echo "Une solution réelle : " . $r1;
                else
                    echo "Deux solutions réelles : " . $r1 . " et " . $r2;
            break;
    }
    echo '</fieldset>';
    echo $after;
}
