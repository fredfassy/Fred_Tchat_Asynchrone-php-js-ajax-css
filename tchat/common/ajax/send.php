<?php 
session_start();
$user = $_SESSION['auth']-> user_id;
$id = $_POST['id'];
$msgField = $_POST['message-send__field'];
// var_dump($_POST);

if(isset($_POST['message-send__field']) && !empty($_POST['message-send__field'])){
    require_once "../inc/db.php";
    $date = date("d-m-Y H:i");
    $req = $pdo->prepare("INSERT INTO message SET user_id = ?, conversation_id = ?, content = ?, created_at = ?");
    $req -> execute([$user,$id,$msgField,$date]);
}