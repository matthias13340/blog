<?php
session_start();

/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');

/* Déclaration des variables */
$view = 'listUser';
$title = 'List user';
$user = '';

$_SESSION['user']['firstNameAdd'] = '';
$_SESSION['user']['lastNameAdd'] = '';
$_SESSION['user']['addUser'] = '';



try {
    /* 1 : On se connecte au serveur de BDD */
    $dbh = connexion();

    /* 2 : On prépare la requête */
    $sql = 
    'SELECT *
    FROM users';

    $stmt = $dbh->prepare($sql);

    /* 3 : Exécute la requête */
    $stmt->execute();

    /* 4 : On recupère le jeux d'enregistrement */
    $usersInfos = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}




include 'tpl/layout.phtml';