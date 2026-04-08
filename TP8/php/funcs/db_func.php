<?php
/**
 * Connexion au serveur de bases de données.
 * Il est nécessaire d'ajuster les paramètres en fonction de votre configuration
 * Si la connexion échoue, un message est affiché et le traitement sur le serveur
 * s'arrête.
 **/
function creerConnexion()
{

    try {

        $nomDeLaBaseDeDonnees = 'aeroport';
        $utilisateurMysql = 'root';
        $motDePasseMysql = '';

        $dsn = 'mysql:host=localhost;charset=utf8;port=3306;dbname=' . $nomDeLaBaseDeDonnees;
        $db = new PDO($dsn, $utilisateurMysql, $motDePasseMysql, array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        erreurExit("Erreur d'accès à la base de données - FIN");
    }

    return $db;
}