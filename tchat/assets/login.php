<?php

if (!empty($_POST['username']) && isset($_POST['pswd'])) {
    session_start();
    require_once "../common/inc/db.php";
    $username = $_POST['username'];
    $req = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $req->execute([$username]);
    $user = $req->fetch();
    if (!password_verify($_POST['pswd'], $user->pswd)) {
        $_SESSION['error'] = "votre compte n'existe pas ou le mot de passe est érroné";
        header("location: ../index.php");
        die();
    } else if ($user->date_creation == NULL) {
        $_SESSION['error'] = "veuillez confirmer votre compte";
        // faire un bouton de renvoi de mail
        header("location: ../index.php");
        die();
    } else {
        $_SESSION['auth'] = $user;
        header("location:../common/read.php");
        die();
    }
}else{
    header("location: ../index.php");
    die();
}
