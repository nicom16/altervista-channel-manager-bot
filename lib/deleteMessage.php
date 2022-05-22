<?php
// Elimina messaggio
function deleteMessage($chatID, $messageID){
	$url = $GLOBALS[website]."/deleteMessage?chat_id=$chatID&message_id=$messageID";
    file_get_contents($url);
}
?>
