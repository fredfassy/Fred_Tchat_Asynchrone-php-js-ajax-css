// script dédié au controle de la validité des champs de création de salon.

function controleChamps() {
  let error = {
    "alert-name": 0,
    "alert-desc": 0,
    "alert-pswd": 0,
  };

  let roomName = document.getElementById("room-create__name").value;
  let roomDesc = document.getElementById("room-create__desc").value;
  let roomCheck = document.getElementById("room-create__check");
  let roomPswd = document.getElementById("room-create__pswd").value;

  const regexRoomName = /^[\w-]+$/;

  let valid = true;

  // Je définie mes conditions pour prévenir les champs vides,
  if (roomName == "") {
    error["alert-name"] = "Le champ Nom du Salon est vide";
    valid = false;
    // puis les formats inadaptés.
  } else if (!regexRoomName.test(roomName)) {
    error["alert-name"] =
      "Le champ 'Nom du Salon' comporte des caractères non autorisés";
    valid = false;
  } else if (roomName.length < 0 || roomName.length > 17) {
    error["alert-name"] = "Le Nom du Salon doit comporter de 1 à 16 caractères";
    valid = false;
  } else {
  }

  if (roomDesc.length < 0 || roomDesc.length > 32) {
    error["alert-desc"] = "Vous êtes limité à 32 caractères";
    valid = false;
  } else {
  }

  // Je définie mes conditions pour prévenir les champs vides,
  if (roomCheck.checked == false) {
  } else if (roomCheck.checked == true && roomPswd == "") {
    error["alert-pswd"] = "Le champ Mot de passe est vide";
    valid = false;
  } else {
    // puis les formats inadaptés.
    if (roomPswd.length < 3 && roomPswd.length > 17) {
      error["alert-pswd"] =
        "Le Mot de passe doit comporter de 4 à 16 caractères";
      valid = false;
    } else {
    }
  }

  if (valid) {
    create();
    return true;
  }
  // sinon j'affiche les messages d'erreur via la variable error
  else {
    for (x in error) {
      if (error[x] != 0) {
        document.getElementById(x).innerHTML = error[x] + "<br>";
        // ensuite je fais disparaitre les messages d'erreurs des champs valide après modif par l'utilisateur
      } else {
        document.getElementById(x).innerHTML = "";
      }
    }
    return false;
  }
}

function create() {
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "ajax/create.php", true);

  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("ok");
    }
  };
  let form = new FormData(document.getElementById("room-create"));
  xhttp.send(form);
  popup();
}
