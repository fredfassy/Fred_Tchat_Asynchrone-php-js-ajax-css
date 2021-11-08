<?php
session_start();
if(isset($_SESSION['auth'])){
    unset($_SESSION['auth']);
    $_SESSION['success']= "Vous êtes à présent déconnecté.";
    header("location: ../index.php");
    die();
}