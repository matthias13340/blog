<?php 

session_start();

/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');


/* Déclaration des variables */
$view = 'index';
$title = 'Index';
$user = '';

userIsLogged();



include 'tpl/layout.phtml';