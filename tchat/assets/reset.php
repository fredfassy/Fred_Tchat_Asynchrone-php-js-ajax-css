<?php
session_start();
if (isset($_POST['password']) && $_POST['password'] == $_POST['confirm'] && !empty($_POST['password'])) {
    $pswd = $_POST['password'];
    $confirm = $_POST['confirm'];
    // je contrôle si le mdp respecte le cadre de longueur défini
    if (strlen($pswd) < 3 && strlen($pswd) > 17) {
        $_SESSION['error'] = "Le Mot de passe doit comporter de 8 à 24 caractères";
        header("location: #");
        die();
        // je contrôle la conformité du mdp avec la confirmation du mdp
    } else if ($pswd !== $confirm) {
        $_SESSION['error'] = "Les 2 Mots de passe doivent être identiques";
        header("location: #");
        die();
    } else {
        $pswd = password_hash($pswd, PASSWORD_BCRYPT);
        require_once '../common/inc/db.php';
        $req = $pdo->prepare('UPDATE users SET token_reset = NULL, date_reset = NULL, pswd = ? WHERE token_reset = ?')->execute([$pswd, $_POST['token']]);
        $_SESSION['success'] = 'mot de passe modifié avec succès !';
        header("location: ../index.php");
        die();
    }
}
if (isset($_GET['token'])) {
    $token = $_GET['token'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../common/css/reset.css" rel="stylesheet">
        <title>Reset</title>
    </head>

    <body>
        <div class="container">
        <form action="Reset.php" method="POST">
            <span>Mot de passe</span><br><input type="password" name="password"><br>
            <input type="text" name="token" readonly hidden value="<?php echo "$token" ?>">
            <span>Confirmer mot de passe</span><br><input type="password" name="confirm"><br>
            <input class="button" type="submit" value="valider" style="margin-top:1rem;">
        </form>
        
    </body>

    </html>
    <?php
}

if (isset($_POST['email'])) {
    require_once '../common/inc/functions.php';
    require_once '../common/inc/db.php';
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $regexEmail = "/^([\w.-])+?\w+[(?=@)]+[@(?=a-z)]+[a-z(?=\-)]+[a-z]+[.]+[a-z]{2,3}$/";
    if (empty($email)) {
        $_SESSION['error'] = "Le champ \"Email\" est vide";
        header("location: #");
        die();
        // je contrôle si l'email ne comporte pas de caractères autres que ceux définis       
    } else if (!preg_match($regexEmail, $email)) {

        $_SESSION['error'] = "Le champ 'Email' comporte des caractères non autorisés";
        header("location: #");
        die();
    } else {
        $req = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $req->execute([$email]);
        // et contrôler si l'email est déjà utilisé dans la base
        $user = $req->fetch();
        if ($user) {
            //Si l'utilisateur existe on lui envoi le mail de reset
            $token = str_random(60);
            $message = "Merci de nous avoir contacté pour reinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous<br/>" . "<a href=\"http://localhost:8088/dwwm/PORTFOLIO/Tchat_Php/tchat/assets/reset.php?token=$token\">Reinitialiser mot de passe</a>";
            if (mail($email, 'Reset mot de passe', $message)) {
                $req = $pdo->prepare('UPDATE users SET token_reset = ?, date_reset = NOW() WHERE email = ?')->execute([$token, $email]);
                $_SESSION['success'] = "Demande de reset envoyé!";
                header("location: ../index.php");
                die();
            }
        } else {
            $_SESSION['error'] = "Aucun compte lié a cette adresse mail";
            header("location: #");
            die();
        }
    }
} else {
    if (!isset($_GET['token'])) {
    ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="../common/css/reset.css" rel="stylesheet">
            <title>Reset</title>
        </head>

        <body>
            <?php
            // j'affiche les messages d'erreurs détectés dans inscription.php
            if (isset($_SESSION['error'])) {
                echo "<div class=\"alertphp\">" . $_SESSION['error'] . "</div>";
                // apres l'affichage du message je supprime la variable de session pour enlever le message en cas de f5
                unset($_SESSION['error']);
            } else if (isset($_SESSION['success'])) {
                echo "<div class=\"successphp\">" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }

            ?>
            <div class="container">
                <form action="reset.php" method="POST">
                    <label for="email"> <span>Entrer votre adresse email:</span> <br>
                        <input id="email" type="email" name="email" placeholder="Adresse Email"><br>
                        <input class="button" type="submit">
                        <a href="../index.php"><input class="button return" type="button" value="Retour"></a>
                    </label>
                </form>
            </div>
        </body>

        </html>
<?php
    }
}
?>