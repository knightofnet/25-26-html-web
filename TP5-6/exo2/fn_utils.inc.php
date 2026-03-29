<?php


/**
 * Récupère l'identifiant à partir d'un tableau source (par exemple $_GET ou $_POST),
 * et retourne l'identifiant si celui-ci est valide, ou -1 sinon
 *
 * @param array $source un tableau source (par exemple $_GET ou $_POST) à partir duquel récupérer l'identifiant
 * @param $idKey string la clé de l'identifiant dans le tableau source (par défaut, la valeur de la clé de l'identifiant est 'id')
 * @return int la valeur de l'identifiant, ou -1 si l'identifiant n'est pas présent dans le tableau source, ou si l'identifiant n'est pas un entier positif
 */
function recupereIdEtRetourneSiValide(array $source, $idKey = 'id')
{
    $idStr = isset($source[$idKey]) ? $source[$idKey] : '';

    if (empty($idStr)) {
        return -1;
    }

    if (!preg_match("/^[0-9]+$/", $idStr)) {
        return -1;
    }

    return intval($idStr);
}