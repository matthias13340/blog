<?php 

session_start();



/* Inclusion des librairies nécessaires */
include('../config/config.php');
include('../librairies/db.lb.php');



/* Vérification role */

userIsLogged($role = 'ROLE_ADMIN');



/* Déclaration des variables */
$view = 'addUser';
$title = 'Add user';
$user = '';

// form
$error = '';
$isInvalidFirst = '';
$isInvalidLast = '';
$isInvalidPassword = '';
$isInvalidUsername = '';
$isInvalidEmail = '';

$username = '';
$firstname = '';
$lastname = '';
$email = '';
$bio = '';


try {

    if (array_key_exists('username', $_POST)) {
        
        

        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $avatar = $_POST['avatar'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordSame = $_POST['passwordSame'];
        $role = $_POST['role'];
        $bio = $_POST['bio'];
        

        /* Vérification caractère spécial username */

        if (ctype_alnum($username) === FALSE){
            $username = '';
            $error = $error.'<li>The username can not contain special characters</li>';
            $isInvalidUsername = 'is-invalid';
            
        };

        $dbh = connexion();
        $sql = 
        'SELECT username
        FROM users
        WHERE username = :username';
        $stmtUsername = $dbh->prepare($sql);
        $stmtUsername->bindValue('username', $username);

        $stmtUsername->execute();
        $usernameInDb = $stmtUsername->fetch(PDO::FETCH_ASSOC);


        if( $usernameInDb['username'] == $username ){
            $username = '';
            $error = $error.'<li>This username is already associated</li>';
            $isInvalidUsername = 'is-invalid';

        }

        /* On vérifie si le nom et prénom conteniennent seulement des lettres */
        if(ctype_alpha($firstname) === false){
            $firstname= '';
            $error = $error.'<li> Firstname can not contain number or special characters </li>';
            $isInvalidFirst = 'is-invalid';

        }

        if (ctype_alpha($lastname) === false){
            $lastname = '';
            $error = $error.'<li>Lastname can not contain number or special characters </li>';
            $isInvalidLast = 'is-invalid';

        }

        /* Vérification adresse mail */
        $dbh = connexion();
        $sql = 
        'SELECT email
        FROM users
        WHERE email = :email';
        $stmtMail = $dbh->prepare($sql);
        $stmtMail->bindValue('email', $email);

        $stmtMail->execute();
        $emailInDb = $stmtMail->fetch(PDO::FETCH_ASSOC);
        var_dump($emailInDb);

        if( $emailInDb['email'] == $email ){
            $email = '';
            $error = $error.'<li>This email is already associated</li>';
            $isInvalidEmail = 'is-invalid';

        }

        if(stristr($email, '@') == ''){
            $email = '';
            $error = $error.'<li>Invalid email</li>';
            $isInvalidEmail = 'is-invalid';
        }

        /* Vérification caractère minimum mdp */

        if(strlen($password) <= PASSWORD_MIN){
            $password = '';
            $passwordSame = '';
            $error = $error.'<li>The password need contain at least '.PASSWORD_MIN .' characters</li>';
            $isInvalidPassword = 'is-invalid';
        };

        /* On vérifie si les mdp correspondent */

        if(strcmp($password, $passwordSame) !== 0) {
            $password = '';
            $passwordSame = '';
            $error = $error.'<li>Password do not match</li>';
            $isInvalidPassword = 'is-invalid';
        }


        var_dump('ERROR'.$error);


        if($error == ''){
            $createdDate = new DateTime();
            /* 1 : On se connecte au serveur de BDD */
            $dbh = connexion();

            /* hashage du mdp */
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            var_dump($passwordHash);

            /* 2 : On prépare la requête */
            $sql = 
            'INSERT INTO `users` (email, password, first_name, last_name, bio, created_date, role, avatar, username)
            VALUES (:email, :password, :first_name, :last_name, :bio, :created_date, :role, :avatar, :username)';

            $stmt = $dbh->prepare($sql);
            $stmt->bindValue('email', $_POST['email']);
            $stmt->bindValue('password', $passwordHash);
            $stmt->bindValue('first_name', $_POST['firstname']);
            $stmt->bindValue('last_name', $_POST['lastname']);
            $stmt->bindValue('bio', $_POST['bio']);
            $stmt->bindValue('created_date', $createdDate->format('Y-m-d H:i:s'));
            $stmt->bindValue('role', $_POST['role']);
            $stmt->bindValue('avatar', $_POST['avatar']);
            $stmt->bindValue('username', $_POST['username']);

            /* 3 : Exécute la requête */
            $stmt->execute();

            $_SESSION['user'] = [
                'firstNameAdd' => $firstname,
                'lastNameAdd' => $lastname,
                'role' => $role,
                'addUser' => 'add to users'
            ];

            header('location: listUser.php');
            exit();

        }
    }   
}
    
catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}




include 'tpl/layout.phtml';



?>