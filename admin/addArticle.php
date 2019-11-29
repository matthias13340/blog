<?php

session_start();

/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');


userIsLogged();

/* Déclaration des variables */
$view = 'addArticle';
$title = 'Add article';
$error = [];
$date = new DateTime();

// form
$subtitle = '';
$picture = '';
$content = '';
$title = '';
$isInvalidTitle = '';
$isInvalidContent = '';


try {
    if(array_key_exists('title', $_POST)){
        $title = $_POST['title'];
        $subTitle = $_POST['subtitle'];
        $content = $_POST['content'];
        $datePublish = $_POST['date'].' '.$_POST['hour'];
        

        if(strlen($content) == 0){
            $error = ['invalidTitle' => 'mandatory content'];
            $isInvalidContent= 'is-invalid';
        }

        if(strlen($title) == 0){
            $error = 'mandatory title';
            $isInvalidTitle= 'is-invalid';
        }

        var_dump($error);
        if($error == ''){

            var_dump($_FILES);
            

            /* 1 : On se connecte au serveur de BDD */
            $dbh = connexion();
            /* 2: On prépare la requête */
            $sql = 
            'INSERT INTO articles (title, created_date, published_date, content,subtitle)
            VALUES (:title, :created_date, :created_date, :published_date, :content, :subtitle)';

            $stmt = $dbh->prepare($sql);
            $stmt->bindValue('title', $title);
            $stmt->bindValue('subTitle', $subtitle);
            $stmt->bindValue('published_date', $datePublish);
            $stmt->bindValue('content', $content);
            $stmt->bindValue('created_date', $createdDate->format('Y-m-d H:i:s'));
            

            /* 3 : Exécute la requête */
            $stmt->execute();

            if(array_key_exists('name',$_FILES)){

            }

            header('location: listArticle.php');
            exit();
        }

    
    }
}
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

include 'tpl/layout.phtml';