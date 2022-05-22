<?php
// Modifica bottoni
function editMessageReplyMarkup ($chatID, $messageID, $n_keys) {
	$keyboard = array (
		"inline_keyboard" => $n_keys,
	);
	$keyboard = json_encode($keyboard);
    $url = $GLOBALS[website]."/editMessageReplyMarkup?chat_id=$chatID&message_id=$messageID&reply_markup=$keyboard";
    $result = file_get_contents($url);
    return $result;
}
?>
