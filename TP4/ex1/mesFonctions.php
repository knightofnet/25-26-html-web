<?php

/**
 * Cette fonction valide que le parametre $chaine ne contient que des majuscules
 */
function estValideMajuscule($chaine) {
	$resultBooleen = preg_match('/^[A-Z]+$/',$chaine);
	return $resultBooleen;
}

function estValideDate($date) {
	return preg_match('/^((0[1-9])|([1-2]\d)|(3[0-1]))\/((0[1-9])|(1[0-2]))\/[1-2]\d{3}+$/',$date);
}

/**
 * Valide que un numéro de téléphone respecte le format français
 */
function estValideNumTel($numTel) {
    return preg_match('/^0[1-9]\d{8}$/',$numTel);
}



?>