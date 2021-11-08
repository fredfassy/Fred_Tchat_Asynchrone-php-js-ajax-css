<?php
session_start();

if ($_SESSION['auth']) {
    require_once "../common/inc/db.php";
    $userAccount = $_SESSION['auth']->user_id;
    $req = $pdo->prepare("SELECT * FROM profile WHERE user_id = $userAccount");
    $req->execute();
    $user = $req->fetch();
    $path = "media/avatar/";


    if(!empty($_POST['password'])) {
        if (password_verify($_POST['password'], $_SESSION['auth']->pswd)) {

            if (!empty($_FILES["avatar"])) {
                $taillemax = 2097152;
                $entensionsValides = array('jpg', 'jpeg', 'gif', 'png');
                $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                if($_FILES['avatar']['size'] <= $taillemax && in_array($extensionUpload, $entensionsValides)){
                    $avatar = $user->id_profile.trim(filter_var($_FILES["avatar"]['name'], FILTER_SANITIZE_STRING));
                    $path = "media/avatar/" .$avatar;
                    $move = move_uploaded_file($_FILES['avatar']['tmp_name'],$path);
                } else {
                    $avatar = $user->avatar;
                }
            }

            if (!empty($_POST["pseudo"])) {
                $pseudo = filter_var($_POST["pseudo"], FILTER_SANITIZE_STRING);
            } else {
                $pseudo = $user->pseudo;
            }
            if (!empty($_POST["age"])) {
                $age = filter_var($_POST["age"], FILTER_SANITIZE_NUMBER_INT);
            } else {
                $age = $user->age;
            }
            if (!empty($_POST["gender"])) {
                $gender = filter_var($_POST["gender"], FILTER_SANITIZE_STRING);
            } else {
                $gender = $user->gender;
            }
            if (!empty($_POST["location"])) {
                $location = filter_var($_POST["location"], FILTER_SANITIZE_STRING);
            } else {
                $location = $user->location;
            }
            $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);


            $req = $pdo->prepare("UPDATE profile SET avatar = ?, pseudo = ?, age = ?, gender = ?, location = ?, description = ? WHERE user_id = $userAccount");
            $req->execute([$avatar, $pseudo, $age, $gender, $location, $description]);
            header("location: read.php");
            die();
        }
    }
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
    <link href="css/update.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <form action="update.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend id="avatar" style="background-image: url(<?php if ($user->avatar != null) {
                                                                    echo "'" . $path . $user->avatar . "'";
                                                                } else {
                                                                    echo "media/sources/default-avatar.png";
                                                                }  ?>);">
                <label> <img id="output" src="media/sources/modifAvatar.png" style="width: auto;">
                    <input type="file" name="avatar" id="ava" onchange="document.getElementById('avatar').style.backgroundImage = 'url' +'(' + window.URL.createObjectURL(this.files[0]) + ')';" style="display:none">
                </label>
            </legend>
            <div class="text">
                <div><b>Pseudo : </b><input type="text" name="pseudo" value="<?php echo $user->pseudo; ?>" /></div>
                <div><b>Âge : </b><input type="number" name="age" min="8" value="<?php echo $user->age; ?>" /> Ans</div>
                <div><b>Genre : </b>
                    <select name="gender">
                        <option value="" selected>Je choisis un genre</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div><b>Localisation :</b>
                    <select name="location">
                        <option value="" selected>Je suis proche de</option>
                        <option value="bourg">Bour-en-Bresse (01)</option>
                        <option value="laon">Laon (02)</option>
                        <option value="moulins">Moulins (03)</option>
                        <option value="digne">Digne (04)</option>
                        <option value="gap">Gap (05)</option>
                        <option value="nice">Nice (06)</option>
                        <option value="privas">Privas (07)</option>
                        <option value="charleville">Charleville-Mézières (08)</option>
                        <option value="foix">Foix (09)</option>
                        <option value="troyes">Troyes (10)</option>
                        <option value="carcassonne">Carcassonne (11)</option>
                        <option value="rodez">Rodez (12)</option>
                        <option value="marseille">Marseille (13)</option>
                        <option value="caen">Caen (14)</option>
                        <option value="aurillac">Aurilac (15)</option>
                        <option value="angouleme">Angoulême (16)</option>
                        <option value="larochelle">La Rochelle (17)</option>
                        <option value="bourges">Bourges (18)</option>
                        <option value="tulle">Tulle (19)</option>
                        <option value="ajaccio">Ajaccio (2A)</option>
                        <option value="bastia">Bastia (2B)</option>
                        <option value="dijon">Dijon (21)</option>
                        <option value="saintbrieuc">Saint-Brieuc (22)</option>
                        <option value="gueret">Guéret (23)</option>
                        <option value="perigueux">Périgueux (24)</option>
                        <option value="besancon">Besançon (25)</option>
                        <option value="lille">Valence (26)</option>
                        <option value="evreux">Evreux (27)</option>
                        <option value="chartres">Chartres (28)</option>
                        <option value="quimper">Quimper (29)</option>
                        <option value="nimes">Nîmes (30)</option>
                        <option value="toulouse">Toulouse (31)</option>
                        <option value="auch">Auch (32)</option>
                        <option value="bordeaux">Bordeaux (33)</option>
                        <option value="montpellier">Montpellier (34)</option>
                        <option value="rennes">Rennes (35)</option>
                        <option value="chateauroux">chateauroux (36)</option>
                        <option value="tours">Tours (37)</option>
                        <option value="grenoble">Grenoble (38)</option>
                        <option value="lons">Lons-le-Saunier (39)</option>
                        <option value="montdemarsan">Mont-de-Marsan (40)</option>
                        <option value="blois">Blois (41)</option>
                        <option value="saintetienne">Saint-Etienne (42)</option>
                        <option value="lepuyenvelay">Le Puy-en-Velay (43)</option>
                        <option value="nantes">Nantes (44)</option>
                        <option value="orleans">Orléans (45)</option>
                        <option value="cahors">Cahors (46)</option>
                        <option value="agen">Agen (47)</option>
                        <option value="mende">Mende (48)</option>
                        <option value="angers">Angers (49)</option>
                        <option value="saintlo">Saint-Lô (50)</option>
                        <option value="chalons">Châlons-en-Champagne (51)</option>
                        <option value="chaumont">Chaumont (52)</option>
                        <option value="laval">Laval (53)</option>
                        <option value="nancy">Nancy (54)</option>
                        <option value="barleduc">Bar-le-Duc (55)</option>
                        <option value="vannes">Vannes (56)</option>
                        <option value="metz">Metz (57)</option>
                        <option value="nevers">Nevers (58)</option>
                        <option value="lille">Lille (59)</option>
                        <option value="beauvais">Beauvais (60)</option>
                        <option value="alencon">Alençon (61)</option>
                        <option value="arras">Arras (62)</option>
                        <option value="clermont">Clermont-Ferrand (63)</option>
                        <option value="pau">Pau (64)</option>
                        <option value="tarbes">Tarbes (65)</option>
                        <option value="perpignan">Perpignan (66)</option>
                        <option value="strasbourg">Strasbourg (67)</option>
                        <option value="colmar">Colmar (68)</option>
                        <option value="lyon">Lyon (69)</option>
                        <option value="vesoul">Vesoul (70)</option>
                        <option value="macon">Mâcon (71)</option>
                        <option value="lemans">Le Mans (72)</option>
                        <option value="chambery">Chambéry (73)</option>
                        <option value="annecy">Annecy (74)</option>
                        <option value="paris">Paris (75)</option>
                        <option value="rouen">Rouen (76)</option>
                        <option value="melun">Melun (77)</option>
                        <option value="versailles">Versailles (78)</option>
                        <option value="niort">Niort (79)</option>
                        <option value="amiens">Amiens (80)</option>
                        <option value="albi">Albi (81)</option>
                        <option value="montauban">Montauban (82)</option>
                        <option value="toulon">Toulon (83)</option>
                        <option value="avignon">Avignon (84)</option>
                        <option value="larochesuryon">La-Roche-sur-Yon (85)</option>
                        <option value="poitiers">Poitiers (86)</option>
                        <option value="limoges">Limoges (87)</option>
                        <option value="epinal">Epinal (88)</option>
                        <option value="auxerre">Auxerre (89)</option>
                        <option value="belfort">Belfort (90)</option>
                        <option value="evry">Evry (91)</option>
                        <option value="nanterre">Nanterre (92)</option>
                        <option value="bobigny">Bobigny (93)</option>
                        <option value="creteil">Créteil (94)</option>
                        <option value="pontoise">Pontoise (95)</option>
                    </select>
                </div>
                <div><b>Description : </b> <br><textarea maxlength="255" rows="6" cols="30" name="description"> <?php echo $user->description; ?></textarea></div>
            </div>
            <div class="input">
                <input type="password" name="password" id="pswd" placeholder="Mot de passe"><br />
                <span class="alertError" id="errorPswd" style="color:#fc7169;"></span>

                <a href="read.php"><input type="button" value="Retour"></a>
                <input type="submit" value="Confirmer" onclick="return controlePassword()">
                <p class="delete" onclick="popup()">Supprimer ce compte</p>
                <div id="popup"">
                    <div class=" popupcontainer">
                    <p>Voulez-vous vraiment supprimer votre compte ?</p>
                    <ul>
                        <li><a href="../assets/delete.php">OUI</a></li>
                        <li><a href="update.php">NON</a></li>
                    </ul>
                </div>
            </div>
            </div>


        </fieldset>
    </form>


    <script>
        function popup() {
            document.getElementById("popup").style.display = "block";
        }
    </script>


    <script>
        function controlePassword() {
            let pswd = document.getElementById("pswd").value;
            if (pswd == "") {
                document.getElementById('errorPswd').innerHTML = "Veuillez confirmer votre mot de passe";
                document.getElementById('errorPswd').style.display = "block";
                return false;
            }
            document.getElementById('errorPswd').style.display = "none";
        }
    </script>

</body>

</html>