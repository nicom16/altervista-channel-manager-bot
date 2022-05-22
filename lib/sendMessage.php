<?php
//Invia messaggio
function sendMessage($chatID, $text){
	$url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=html&text=".urlencode($text);
    $result = file_get_contents($url);
    return $result;
}
?>
