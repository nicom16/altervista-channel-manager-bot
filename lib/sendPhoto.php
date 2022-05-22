<?php
// Invia immagine
function sendPhoto ($chatID, $pic, $caption, $keys) {
	if (empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendPhoto?parse_mode=HTML&chat_id=$chatID&photo=$pic";
	} elseif (!empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendPhoto?parse_mode=HTML&chat_id=$chatID&photo=$pic&caption=".urlencode($caption);
	} elseif (empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendPhoto?parse_mode=HTML&chat_id=$chatID&photo=$pic&reply_markup=$keyboard";
	} elseif (!empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendPhoto?parse_mode=HTML&chat_id=$chatID&photo=$pic&caption=".urlencode($caption)."&reply_markup=$keyboard";
	}
	$result = file_get_contents($url);
	return $result;
}

?>
