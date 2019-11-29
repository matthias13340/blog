<?php 

session_start();

/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');

/* Déclaration des variables */

$view = 'login';
$title = 'Login';
$user = '';

// form
$error = '';
$log = '';

$isInvalidPassword = '';
$isInvalidEmail = '';

$password = '';
$email = '';


try {
    if(array_key_exists('email',$_POST)){
        $email = $_POST['email'];
        $password = $_POST['password'];

        /* Vérification adresse mail */
        $dbh = connexion();
        $sql = 
        'SELECT *
        FROM users
        WHERE email = :email';
        $stmtMail = $dbh->prepare($sql);
        $stmtMail->bindValue('email', $email);

        $stmtMail->execute();
        $userLog = $stmtMail->fetch(PDO::FETCH_ASSOC);
        var_dump($userLog);
        
        if($userLog['email'] != false){
            $log = 'ok';
        }

        $hash = $userLog['password'];

        $verify = password_verify($password, $hash);
        var_dump($verify);

        if($verify == true){
            $log = 'ok';
        }
        else{
            header('location: login.php');
            exit();
           
        }


        if($log == 'ok'){
            $date = new DateTime();
            $dbh = connexion();

            $sql = 
            'UPDATE users
            SET last_login_date = :date
            WHERE email = :email';

            $stmt = $dbh->prepare($sql);
            $stmt->bindValue('date', $date->format('Y-m-d H:i:s'));
            $stmt->bindValue('email', $email);

            $stmt->execute();


            $_SESSION['connected'] = true;
            $_SESSION['user'] = [
                'id' => $userLog['id'],
                'firstName' => $userLog['first_name'],
                'lastName' => $userLog['last_name'],
                'role' => $userLog['role']
            ];
            header('location: index.php');
            exit();
        }

    }

}
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


include 'tpl/login.phtml';