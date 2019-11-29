<?php
session_start();

/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');


$view = 'listArticle';
$title = 'List article';





include 'tpl/layout.phtml';