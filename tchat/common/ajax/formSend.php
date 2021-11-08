<?php
    require_once "../inc/db.php";
    $id = $_POST["id"];
    
echo "<form action=\"#\" method=\"POST\" class=\"chat__msg-form\" id=\"$id\" onsubmit=\"return link($id);\">";
echo "<input type=\"text\" class=\"message-send__field\" id=\"message-send__field\" name=\"message-send__field\" placeholder=\"Tapez votre message...\">";
echo "<input type=\"text\" value=\"$id\" name=\"id\" style=\"display:none\">";
echo "<button type=\"submit\" class=\"message-send__button\" id=\"message-send__button\">";
echo "<i class=\"fa fa-paper-plane\"></i>";
echo "</button>";
echo "</form>"; 
