<?php
require_once "../inc/db.php";
$id = $_POST["id"];
$req = $pdo->prepare("SELECT conv_img,conv_name,conv_desc FROM conversation WHERE conversation_id = ?");
$req->execute([$id]);
$roomInfos = $req->fetch();

echo "<div id=\"$id\"class=\"room__infos\">
        <h2 id=\"room__title\">$roomInfos->conv_name</h2>";
        echo "<div class='room-info__img' style='background-image: url(";
        if($roomInfos->conv_img){
           echo "\"./media/room-img/$roomInfos->conv_img\"";
       }else {
           echo "\"./media/sources/default-channel.png\"";
       };
       echo ")';></div>";
           
            echo "<p id=\"room__desc\">$roomInfos->conv_desc</p>
            <hr/>
            <div class='room-info-Participants'>
                <div class='room-info-Participants__avatar' style='background-image: url()'></div>
            </div>
            
    </div>";
?> 