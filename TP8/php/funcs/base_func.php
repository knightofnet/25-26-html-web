<?php
function redirectTo($url) {
    header("Location: $url");
}

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

    exit;
}