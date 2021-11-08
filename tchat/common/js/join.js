

let test = null;

function join(id) {
  test2();
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "ajax/message.php", true);
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("chat__msg-area").innerHTML = this.responseText;
    }
  };
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhttp.send("id=" + id);
  test = setInterval(function kekw() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/message.php", true);
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("chat__msg-area").innerHTML = this.responseText;
      }
    };
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id);
  }, 1000);

  var formCreat = new XMLHttpRequest();
  formCreat.open("POST", "ajax/formSend.php", true);
  formCreat.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("chat__msg-send").innerHTML = this.responseText;
    }
  };
  formCreat.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded"
  );
  formCreat.send("id=" + id);
  setTimeout(() => {
    enbas();
  }, 100);
  
  var roomInfos = new XMLHttpRequest();
roomInfos.open("POST", "ajax/roomInfos.php", true);
roomInfos.onreadystatechange = function () {
  if (this.readyState == 4 && this.status == 200) {
    document.getElementById('sidebar-right').innerHTML = this.responseText;
  }
};
roomInfos.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded"
  );
roomInfos.send("id="+id);
}
function test2() {
  if (test != null) {
    clearInterval(test);
  }
}

function link(id) {
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "ajax/send.php", true);
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("message-send__field").value = "";
    }
  };
  let form = new FormData(document.getElementById(id));
  console.log(form);
  xhttp.send(form);
  join(id);
  setTimeout(() => {
    enbas();
  }, 100);

  return false;
}
function enbas() {
  document.getElementById("chat__msg-area").scrollTo({
    top: 100000000,
    left: 0,
    behavior: 'smooth'
  });
}