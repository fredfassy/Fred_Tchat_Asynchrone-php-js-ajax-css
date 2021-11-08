<?php
session_start();
require_once '../common/inc/functions.php';
require_once '../common/inc/db.php';

if (!empty($_POST['validation'])) {

    // Pour améliorer la lisibilité du code, je définie mes variables
    // et je les sanitize directement pour sécuriser les champs.
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $regexEmail = "/^([\w.-])+?\w+[(?=@)]+[@(?=a-z)]+[a-z(?=\-)]+[a-z]+[.]+[a-z]{2,3}$/";
    $pswd = $_POST['pswd'];
    // je définie une variable dédiée au contrôle des caractères tolérés dans le nom
    $regexUser = "/^[\w-]+$/";
    // $mdp = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confirm = $_POST['confirm'];

    // ==============================   contrôle du Nom d'utilisateur  ==============================
    // je contrôle si le champ nom est vide 
    if (empty($username)) {
        $_SESSION['error'] = "Le champ 'Nom d'utilisateur' est vide";
        header("location: ../index.php");
        die();
        // ensuite je contrôle si le nom respecte le cadre de longueur défini
    } else if (strlen($username) < 3 && strlen($username) > 17) {
        $_SESSION['error'] = "Le 'Nom d'utilisateur' doit comporter de 4 à 16 caractères";
        header("location: ../index.php");
        die();
        // je contrôle si le nom ne comporte pas de caractères autres que ceux définis
    } else if (!preg_match($regexUser, $username)) {
        $_SESSION['error'] = "Le champ 'Nom d'utilisateur' comporte des caractères non autorisés";
        header("location: ../index.php");
        die();
    } else {
        $req = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $req -> execute([$username]);
        // et contrôler si le nom n'est pas déjà utilisé dans la base
        $user = $req -> fetch();
        if ($user){
            // sinon j'envoie un message d'alerte et redirige vers l'inscription
            $_SESSION['error'] = "Pseudo déjà utilisé";
            header("location: ../index.php");
            die();
        }
    }

    // ==============================   contrôle de l'Email   ==============================
    // je contrôle si le champ email est vide 
    if (empty($email)) {
        $_SESSION['error'] = "Le champ \"Email\" est vide";
        header("location: ../index.php");
        die();
        // je contrôle si l'email ne comporte pas de caractères autres que ceux définis       
    } else if (!preg_match( $regexEmail,$email)) {
        
        $_SESSION['error'] = "Le champ 'Email' comporte des caractères non autorisés";
        header("location: ../index.php");
        die();
    } else {
        $req = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $req -> execute([$email]);
        // et contrôler si l'email n'est pas déjà utilisé dans la base
        $user = $req -> fetch();
        if ($user){
            // sinon j'envoie un message d'alerte et redirige vers l'inscription
            $_SESSION['error'] = "Email déjà utilisé";
            header("location: ../index.php");
            die();
        }
        }


    // ==============================   controle du mot de passe   ==============================
    // je contrôle si le champ mot de passe est vide
    if (empty($pswd)) {
        $_SESSION['error'] = "Le champ \"Mot de passe\" est vide";
        header("location: ../index.php");
        die();
        // je contrôle si le mdp respecte le cadre de longueur défini
    } else if (strlen($pswd) < 3 && strlen($pswd) > 17) {
        $_SESSION['error'] = "Le Mot de passe doit comporter de 8 à 24 caractères";
        header("location: ../index.php");
        die();
        // je contrôle la conformité du mdp avec la confirmation du mdp
    } else if ($pswd !== $confirm) {
        $_SESSION['error'] = "Les 2 Mots de passe doivent être identiques";
        header("location: ../index.php");
        die();
    } else {
        $pswd = password_hash($pswd, PASSWORD_BCRYPT);
    }

    // A ce stade, si tous les contrôles sont passés avec succès, on va se connecter à la base de données

    // je crée un token pour envoyer à la base de donnéesxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    $token = str_random(60);
    // je prépare et j'envoie les données vers la base de données
    $req = $pdo->prepare ("INSERT INTO users SET username = ?, email = ?, pswd = ?, token_conf = ?, roles = 'membre'");
    $req -> execute([$username,$email,$pswd,$token]);
    // On recupere l'id de l'utilisateurs qu'on vient de créer
    $req = $pdo->prepare('SELECT user_id FROM users where username = ?');
        $req->execute([$username]);
        $user = $req->fetch();
        $id = $user->user_id ;
    
    $message = "Merci de vous être enregistré, pour finaliser l'inscription, veuillez cliquer sur le lien ci-dessous<br/>" . "<a href=\"http://localhost:8088/dwwm/PORTFOLIO/Tchat_Php/tchat/assets/confirm.php?token=$token&id=$id\">Confirmer votre compte</a>";
     if(mail($email, 'Confirmez votre inscription', $message)){
        $_SESSION['success'] = "Inscription validée, merci de la finaliser en cliquant sur le lien envoyé sur votre boite mail !";
        header("location: ../index.php");
        die();
} else {
    header("location: ../index.php");
    die();
}
    // ==============================   Si les champs sont vides - else lié au (!empty($_POST))   ==============================   
} else {
    $_SESSION['error'] = "Le Formulaire est vide, merci de renseigner chaque champ";
    header("location: ../index.php");
}


