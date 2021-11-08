<?php
session_start();
if (isset($_POST) && !empty($_POST)) {
    //on verifie nos champs et set nos variable

    if (empty($_POST['room-create__name'])) {
        $_SESSION["error"] = "Veuillez saisir un nom pour le nouveau salon";
        header("location: ../");
        die();
    } else {
        require_once "../inc/db.php";
        $roomName = $_POST['room-create__name'];
        $req = $pdo->prepare("SELECT * FROM conversation WHERE conv_name = ?");
        $req->execute([$roomName]);
        $room = $req->fetch();
        if ($room) {
            $_SESSION["error"] = "nom de salon déjà attribué";
            header("location: ../");
            die();
        }
    }
    if (empty($_POST["room-create__desc"])) {
        $roomDesc = NULL;
    } else $roomDesc = $_POST["room-create__desc"];

    if (!isset($_POST['room-create__check'])) {
        $roomPswd = NULL;
        $roomPrivate = 0;
    } else if (empty($_POST['room-create__pswd'])) {
        $_SESSION["error"] = "Veuillez choisir un mot de passe pour valider l'accès au salon privé.";
        header("location: ../");
        die();
    } else {
        $roomPswd = $_POST['room-create__pswd'];
        $roomPrivate = 1;
    }
    if (!empty($_FILES["room-create__img-upload"])) {

        $taillemax = 2097152;
        $entensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        $extensionUpload = strtolower(substr(strrchr($_FILES['room-create__img-upload']['name'], '.'), 1));
        if ($_FILES['room-create__img-upload']['size'] <= $taillemax && in_array($extensionUpload, $entensionsValides)) {
            $roomBgImg = filter_var($_FILES["room-create__img-upload"]['name'], FILTER_SANITIZE_STRING);
            $path = "../media/room-img/" . $roomBgImg;
            $move = move_uploaded_file($_FILES['room-create__img-upload']['tmp_name'], $path);
        } else {
            $roomBgImg = NULL;
        }
    } else {
        $roomBgImg = NULL;
    }
    
    $req = $pdo->prepare("INSERT INTO conversation SET conv_name = ?, conv_private = ?,conv_pswd = ?,conv_desc=?, conv_img=?");
    $req->execute([$roomName, $roomPrivate, $roomPswd, $roomDesc, $roomBgImg]);
    $req = $pdo->prepare("SELECT * FROM conversation WHERE conv_name = ?");
    $req->execute([$roomName]);
    $room = $req->fetch();
    $req = $pdo->prepare("INSERT INTO participant SET user_id=?, conversation_id=? ");
    $req->execute([$_SESSION['auth']->user_id, $room->conversation_id]);
} else {
    $_SESSION["error"] = "Une erreur est survenue pendant la création du salon";
    header("location: ../");
    die();
}
