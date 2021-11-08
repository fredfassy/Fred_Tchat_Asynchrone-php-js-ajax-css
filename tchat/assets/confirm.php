<?php
if (isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];
    require_once "../common/inc/db.php";
    $req = $pdo->prepare('SELECT token_conf FROM users WHERE user_id = ?');
    $req->execute([$id]);
    $user = $req->fetch();
    $user = $user->token_conf;
    // si le token de la base de données correspond au token de l'url, on valide le compte, on supprime le token de la base et on génère un datetime
    if ($user == $token) {
        session_start();
        $pdo->prepare("UPDATE users SET token_conf = NULL, date_creation = NOW() WHERE user_id = $id; INSERT INTO profile SET user_id = $id")->execute();
        $_SESSION['success'] = "Compte créé avec succès, bienvenue !";
        header("location: ../index.php");
        die();
    } else {
        header("location: ../index.php");
        die();
    }
} else {
    header("location: ../index.php");
    die();
}
