<?php
session_start();
if (isset($_POST) && !empty($_POST)) {
    require_once "../inc/db.php";
    $id = $_POST['id'];
    $req = $pdo->prepare("SELECT * FROM message WHERE conversation_id = ?");
    $req->execute([$id]);  
                if ($messages = $req->fetchAll()) {
                    foreach ($messages as $message) {
                        
                        $req = $pdo->prepare("SELECT avatar,pseudo FROM profile WHERE user_id = $message->user_id");
                        $req->execute();
                        $user = $req->fetch();

                        $path = "media/avatar/";
                        $noImg = "default-avatar.png";
                          if ($user->avatar == NULL) {
                            $path = "media/sources/";
                            $user->avatar = $noImg;
                          }

                        // $path = "media/avatar/";
                        // if ($user != NULL) {
                        //     $image = $user->avatar;    
                        // } else {
                        //     $image = "default-avatar.png"; 
                        //     $path = "media/sources/";
                        // }

                        $req = $pdo->prepare("SELECT username FROM users WHERE user_id = $message->user_id");
                        $req->execute();
                        $userDefault = $req->fetch();
                          if ($user->pseudo == NULL){
                            $user->pseudo = $userDefault->username;
                          }

                        if($message->user_id == $_SESSION['auth']->user_id){
                        echo    "<div id=\"myTchat__box\">
                                    <div class=\"myMessage\">
                                        <p class=\"chat__area-msg-pseudo\">$user->pseudo</p>
                                        <p>$message->content</p>
                                        <p class=\"datetime\">$message->created_at</p>
                                    </div>
                                        <img id=\"tchat__avatar\" style=\"background-image:url($path$user->avatar);\"></img>
                                </div>";
                    } else{
                        echo    "<div id=\"tchat__box\">
                                        <img id=\"tchat__avatar\" style=\"background-image:url($path$user->avatar);\"></img>
                                    <div class=\"otherMessage\">
                                        <p class=\"chat__area-msg-pseudo\">$user->pseudo</p>
                                        <p>$message->content</p>
                                        <p class=\"datetime\">$message->created_at</p>
                                    </div>
                                </div>";

                    }
                
                }
                }
}
