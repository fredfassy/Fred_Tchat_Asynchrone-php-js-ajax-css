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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link href="css/hub.css" rel="stylesheet">
    <title>hub</title>
</head>

<body>


    <main>

        <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx LEFT SIDEBAR  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->

        <section class="sidebar-left">
            <!--xxxxxx User xxxxxx-->
            <div class="sidebar__user">

                <div class="user__avatar">

                    <div id="avatar" style="background-image: url(<?php if ($user->avatar != null) {
                                                                        echo "'" . $path . $user->avatar . "'";
                                                                    } else {
                                                                        echo "media/sources/default-avatar.png";
                                                                    }  ?>);">
                    </div>

                </div>

                <div class="user__info">
                    <h3><?php if ($user->pseudo == NULL) {
                            echo $_SESSION['auth']->username;
                        } else {
                            echo $user->pseudo;
                        }  ?></h3>
                    <a href="read.php"><input class="user__info-read" type="submit" value="Mon Profil"></a>
                    <a class="user__info-logout" href="../assets/logout.php">Déconnexion</a>
                </div>

            </div>
            <!--xxxxxx Room Search xxxxxx-->
            <div class="sidebar__room-manage">

                <div class="room-manage__searchBar">
                    <button type="submit" class="room-manage__searchBar__button">
                        <i class="fa fa-search"></i>
                    </button>
                    <input type="text" class="room-manage__field" name="username" placeholder="Recherche un salon...">
                </div>

                <div class="room-manage-add"><i class="fas fa-plus-circle" onclick="popup()"></i>
                    <a href="#" onclick="popup()">Ajouter Salon</a>
                </div>


            </div>

            <!--xxxxxx Room List xxxxxx-->



            <div class="sidebar__room-list">
                <?php
                $req = $pdo->prepare("SELECT * FROM conversation ORDER BY conversation_id DESC");
                $req->execute();
                $rooms = $req->fetchAll();

                foreach ($rooms as $room) {
                    echo "<div class='room'onclick=\"join($room->conversation_id)\">";
                    echo "<div class='room__img' style='background-image: url(";
                    if ($room->conv_img) {
                        echo "\"./media/room-img/$room->conv_img\"";
                    } else {
                        echo "\"./media/sources/default-channel.png\"";
                    }
                    echo ");'></div>";
                    echo "<div class='room-infos'><b>$room->conv_name</b><p>$room->conv_desc</p></div></div>";
                }

                ?>

            </div>

        </section>
        <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx HUB  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
        <section class="chat">

            <div class="chat__msg-area" id="chat__msg-area"> 
            </div>

            <div class="chat__msg-send" id="chat__msg-send">
               
            </div>

        </section>

        <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx RIGHT SIDEBAR  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->

        <div class="sidebar-right" id="sidebar-right">

        </div>


    </main>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Popup Creation Salon  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->

    <div id="popup"">
    <div class=" popup__container">
        <form id="room-create" action="ajax/create.php" method="POST" enctype="multipart/form-data">
            <span class="alert-error" id="alert-name"></span>
            <span class="alert-error" id="alert-desc"></span>
            <span class="alert-error" id="alert-pswd"></span>
            <legend id="room-create__img" style="background-image: url(<?php echo "media/sources/default-channel.png"; ?>);">
                <label> <img id="room-create__img-mask" src="media/sources/modifImg.png" style="width:auto;">
                    <input type="file" name="room-create__img-upload" id="room-create__img-upload" onchange="document.getElementById('room-create__img').style.backgroundImage = 'url' +'(' + window.URL.createObjectURL(this.files[0]) + ')';" style="display:none">
                </label>
            </legend>
            <h3>Paramètres du Salon</h3>
            <input id="room-create__name" type="text" name="room-create__name" placeholder="Nom du Salon">
            <div><b>Description : </b>
                <br>
                <textarea maxlength="255" rows="1" cols="20" id="room-create__desc" name="room-create__desc" placeholder="vous avez 32 caractères..." maxlength="32" style="resize:none;width:80%;"></textarea>
            </div>
            <b>Salon privé </b>
            <input type="checkbox" id="room-create__check" name="room-create__check">
            <input type="password" name="room-create__pswd" id="room-create__pswd" placeholder="Mot de passe" disabled>
            <i class="far fa-eye" id="room-create__pswd-see" onclick="seePswd()" style="visibility:hidden;"></i>
            <ul>
                <li><a href="#" onclick="return controleChamps();">Créer</a></li>
                <li><a href="#" onclick="popup()">Retour</a></li>
            </ul>
        </form>
    </div>
    </div>
    <script src="js/popup.js"></script>
    <script src="js/see.js"></script>
    <script src="js/create.js"></script>
    <script src="js/join.js"></script>
</body>

</html>