<?php

/* Inclusion des librairies nécessaires */
include('../librairies/db.lb.php');

$_SESSION['connected'] = false;
unset($_SESSION['connected']);
header('Location: login.php');
exit();