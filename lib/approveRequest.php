<?php 

function approveRequest ($channel, $id) {
   $url = $GLOBALS[website]."/approveChatJoinRequest?chat_id=$channel&user_id=$id";
    return file_get_contents($url);
}

?>
