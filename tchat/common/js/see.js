let roomPswdSee = document.getElementById("room-create__pswd-see");
let roomPswd = document.getElementById("room-create__pswd");
let roomCheck = document.getElementById("room-create__check");
    roomCheck.addEventListener("change", function(event) {
        if (event.target.checked) {
            roomPswdSee.style.visibility = "visible";
            roomPswd.disabled = false;
            
            
        } else {roomPswd.disabled = true; roomPswd.value = "";roomPswdSee.style.visibility = "hidden";}
    }, false);


function seePswd(){
    roomPswd.type == "password" ? roomPswd.type  = "text" : roomPswd.type  = "password";
    roomPswdSee.className == "far fa-eye" ? roomPswdSee.className  = "far fa-eye-slash" : roomPswdSee.className = "far fa-eye";
}