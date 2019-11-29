<?php

function connexion() {
    $dbh = new PDO ('mysql:host=localhost;dbname=blog', DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}


function userIsLogged ($role ='ROLE_AUTHOR'){

    if(!isset($_SESSION['connected']) || $_SESSION['connected'] != true){
        header('Location: login.php');
        exit();
    }
    else if(isset($_SESSION['user']) && $_SESSION['user']['role'] != 'ROLE_ADMIN' &&  $_SESSION['user']['role'] != $role){
        header('Location: index.php');
        exit();
    }
}
