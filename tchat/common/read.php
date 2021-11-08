<?php

session_start();

if ($_SESSION['auth']) {
    require_once "../common/inc/db.php";
    $userAccount = $_SESSION['auth']->user_id;
    $req = $pdo->prepare("SELECT * FROM profile WHERE user_id = $userAccount");
    $req->execute();
    $user = $req->fetch();
    $path = "media/avatar/";
} else {
    header("location: ../index.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/read.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <fieldset class="container">
        <legend id="avatar" style="background-image: url(<?php if ($user->avatar != null) {
                                                                echo "'" . $path . $user->avatar . "'";
                                                            } else {
                                                                echo "media/sources/default-avatar.png";
                                                            }  ?>);"></legend>
        <div class="text">
            <div><b>Pseudo : </b><span><?php if ($user->pseudo == NULL){echo $_SESSION['auth']->username;}else{echo $user->pseudo;} ?></span></div>
            <div><b>Âge : </b><span><?php echo $user->age; ?> ans</span></div>
            <div><b>Genre : </b><span><?php echo $user->gender; ?></span></div>
            <div><b>Localisation : </b><span><?php echo $user->location; ?></span></div>
            <div><b>Description : </b> <br> <span><?php echo $user->description; ?></span></div>
        </div>
        <div class="input">
            <a href="index.php"><input type="button" value="Accéder au Tchat"></a>
            <a href="update.php"><input type="submit" value="Modifier Profil"></a>
        </div>

        <a class="logout" href="../assets/logout.php">Deconnexion <i class="fas fa-door-open"></i></a>
    </fieldset>

</body>

</html>