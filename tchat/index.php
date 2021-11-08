<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="common/css/style.css" rel="stylesheet">
    <title>Formulaire Inscription/Connexion</title>
</head>

<body>
    <?php
    // j'affiche les messages d'erreurs détectés dans inscription.php
    session_start();
    if(isset($_SESSION['error'])){
        echo "<div class=\"alertphp\">" . $_SESSION['error'] . "</div>";
    // apres l'affichage du message je supprime la variable de session pour enlever le message en cas de f5
        unset($_SESSION['error']);
    } else if (isset($_SESSION['success'])){
        echo "<div class=\"successphp\">" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    }

?>
    <div class="container">
        <div class="bluebg">
            <div class="box signin">
                <h2>Déjà Inscrit ?</h2>
                <button class="signinBtn">Connectez vous</button>
            </div>
            <div class="box signup">
                <h2>Vous n'avez pas de compte ?</h2>
                <button class="signupBtn">Inscrivez vous</button>
            </div>
        </div>
        <div class="formBx">
            <div class="form signinForm">
                <form action="assets/login.php" method="POST">
                    <h3>Connectez vous</h3>
                    <input type="text" name="username" placeholder="Nom d'utilisateur">
                    <input type="password" name="pswd" placeholder="Mot de passe">
                    <input type="submit" value="Se connecter">
                    <a href="assets/reset.php" class="forgot">Mot de passe oublié</a>
                </form>
            </div>

            <div class="form signupForm">
                <form action="assets/signup.php" method="POST">
                    <h3>Inscrivez vous</h3>
                    <span class="information">Le Nom d'utilisateur doit comporter de 4 à 16 caractères</span>
                    <input id="inscr_username" type="text" name="username" placeholder="Nom d'utilisateur">
                    <span  class="redalert" id="errorusername"></span>
                    <span class="information">Le format de votre Email doit respecter les standards</span>
                    <input id="inscr_email" type="email" name="email" placeholder="Adresse Email">
                    <span class="redalert" id="erroremail"></span>
                    <span class="information">Le Mot de passe doit comporter de 8 à 24 caractères</span>
                    <input id="inscr_pswd" type="password" name="pswd" placeholder="Mot de passe">
                    <span class="redalert" id="errorpswd"></span>
                    <span class="information">Les 2 mots de passe doivent être identiques</span>
                    <input id="inscr_confirm" type="password" name="confirm" placeholder="Confirmer Mot de passe">
                    <span class="redalert" id="errorpswd2"></span>
                    <!-- input masqué dédié à la validation des conditions en php  il va etre posté avec les autres données et etre vide saudf si le javadscript fait toutes les verifs et qu'il n'y a pas de probleme-->
                    <input type="text" id="validation" name="validation" hidden>
                    <input type="submit" onclick="return controleChamps();" value="S'inscrire">
                </form>
            </div>
        </div>
    </div>


    <script>                                                            
        // Script dédié à l'animation de l'interface inscription/connexion.

        //définition des constantes
        const signinBtn = document.querySelector('.signinBtn');
        const signupBtn = document.querySelector('.signupBtn');
        const formBx = document.querySelector('.formBx');
        const body = document.querySelector('body');

        // Animation
        signupBtn.onclick = function() {
            formBx.classList.add('active')
            body.classList.add('active')
        }

        signinBtn.onclick = function() {
            formBx.classList.remove('active')
            body.classList.remove('active')
        }
    </script>

    <script>
        // script dédié au controle de la validité des champs d'inscription.

        function controleChamps() {
            let error = {
                "errorusername":0,
                "erroremail":0,
                "errorpswd":0,
                "errorpswd2":0
            }

            // pour optimiser la lisibilité je définie des variables affiliées à document.getElementById.
            let username = document.getElementById('inscr_username').value;
            let email = document.getElementById('inscr_email').value;
            let pswd = document.getElementById('inscr_pswd').value;
            let pswd2 = document.getElementById('inscr_confirm').value;

            //si la variable prends une valeur ca valide l'execution du php dans inscription.php
            let validation = document.getElementById('validation');

            // variable dédiée au respect du format du Nom d'utilisateur.
            const regexUser = /^[\w-]+$/;
            //variable dédiée au respect du format de l'email + regex maison
            const regexEmail =  /^([\w.-])+?\w+[(?=@)]+[@(?=a-z)]+[a-z(?=\-)]+[a-z]+[.]+[a-z]{2,3}$/;

            // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
            let valid = true;

            // ==============================   contrôle du Nom d'utilisateur  ==============================
            // Je définie mes conditions pour prévenir les champs vides,
            if (username == "") {
                error["errorusername"] = "Le champ Nom d'utilisateur est vide";
                valid = false;
            } else {
            // puis les formats inadaptés.
                if (!regexUser.test(username)) {
                    error["errorusername"] = "Le champ 'Nom d'utilisateur' comporte des caractères non autorisés";
                    valid = false;
                } else {
                    if (username.length > 3 && username.length < 17) {
                    } else {
                        error["errorusername"] = "Le Nom d'utilisateur doit comporter de 4 à 16 caractères";
                        valid = false;
                    }
                }
            }


            // ==============================   contrôle de l'Email   ==============================
            // Je définie mes conditions pour prévenir les champs vides,
            if (email == "") {
                error["erroremail"] = "Le champ Email est vide";
                valid = false;
            } else {
            // puis les formats inadaptés.
                if (!regexEmail.test(email)) {
                    error["erroremail"] = "Le format de votre Email n'est pas valide";
                    valid = false;
                } else{
                }

            }

            // ==============================   controle du mot de passe   ==============================
            // Je définie mes conditions pour prévenir les champs vides,
            if (pswd == "") {
                error["errorpswd"] = "Le champ Mot de passe est vide";
                valid = false;
            } else {
            // puis les formats inadaptés.
                if (pswd.length > 7 && pswd.length < 25) {
            // si le format du mdp est bon, je vérifie la bonne correspondance des 2 mdp.
                    if (pswd != pswd2) {
                        error["errorpswd2"] = "Les 2 mots de passe doivent être identiques";
                        valid = false;
                    }

                } else {
                    error["errorpswd"] = "Le Mot de passe doit comporter de 8 à 24 caractères";
                    valid = false;
                }
            }


            // ============  si tous les champs sont correctement renseignés, envoi du formulaire  ============
            if(valid){
                validation.value = "ok";
                return true;
            }
            // sinon j'affiche les messages d'erreur via la variable error
            else{
                for(x in error){
                    if(error[x] != 0){
                        document.getElementById(x).innerHTML = error[x];
            // ensuite je fais disparaitre les messages d'erreurs des champs valide après modif par l'utilisateur
                     } else {
                         document.getElementById(x).innerHTML = "";
                     }
                }
                return false;
            }
        }
    </script>
</body>

</html>