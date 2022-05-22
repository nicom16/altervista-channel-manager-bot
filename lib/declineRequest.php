<?php 

function declineRequest ($channel, $id) {
   $url = $GLOBALS[website]."/declineChatJoinRequest?chat_id=$channel&user_id=$id";
    return file_get_contents($url);
}

?>
