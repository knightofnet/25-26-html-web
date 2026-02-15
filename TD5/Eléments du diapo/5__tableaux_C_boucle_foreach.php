<?php
$agesParPrenoms = [
    "Alice" => 30,
    "Bob" => 25,
    "Charlie" => 35
];

/*
On va parcourir le tableau $agesParPrenoms avec une boucle foreach.
La syntaxe de la boucle foreach est la suivante :

    foreach ($tableau as $cle => $valeur) {
        // code à exécuter pour chaque élément du tableau
    }   

Pour chaque élément du tableau $agesParPrenoms, la variable $prenom contiendra 
la clé (le prénom) et la variable $age contiendra la valeur (l'âge).
Une fois que nous avons accès à ces variables, nous pouvons les utiliser pour afficher
les informations de chaque personne.
*/
foreach ($agesParPrenoms as $prenom => $age) {
    echo "$prenom a $age ans.<br>";
}
