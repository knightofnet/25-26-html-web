<?php

// Récupère la note depuis les paramètres GET, ou utilise 0 par défaut
// Utilisez l'URL suivante pour tester : http://localhost:8000/TD5/3__condition__B.php?note=15
// 
// Remarques :
// - Remplacez 15 par la note que vous souhaitez tester
// - Dans l'URL, le paramètre "note" correspond à la note de l'étudiant
// - La fonction intval() convertit la valeur de $_GET['note'] en entier (ou 0 si la conversion échoue)
// - La fonction isset() vérifie si le paramètre "note" est présent dans l'URL
// - Si le paramètre "note" n'est pas présent, la variable $note sera initialisée à 0
// - Remplacez 'http://localhost:8000/TD5/' par l'URL appropriée en fonction de votre configuration de serveur local
//
$note = isset($_GET['note']) ? intval($_GET['note']) : 0;
if ($note >= 10) {
    echo "Admis";
} else {
    echo "Non admis";
}
