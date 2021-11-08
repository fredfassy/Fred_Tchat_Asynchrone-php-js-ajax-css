<?php
session_start();
if(isset($_SESSION['auth'])){
    require_once "../common/inc/db.php";
    $userAccount = $_SESSION['auth']->user_id;
    $req = $pdo->prepare("DELETE FROM profile WHERE user_id = $userAccount")->execute();
    $req = $pdo->prepare("DELETE FROM users WHERE user_id = $userAccount")->execute();
    unset($_SESSION['auth']);
    $_SESSION['success']= "Compte supprimé avec succès";
    header("location: ../index.php");
    die();
}else{
    header("location: ../index.php");
    die();
}