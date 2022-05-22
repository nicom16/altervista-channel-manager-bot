<?php
// Invia GIF
function sendGif ($chatID, $gif, $caption, $keys) {
	if (empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendAnimation?parse_mode=HTML&chat_id=$chatID&animation=$gif";
	} elseif (!empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendAnimation?parse_mode=HTML&chat_id=$chatID&animation=$gif&caption=".urlencode($caption);
	} elseif (empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendAnimation?parse_mode=HTML&chat_id=$chatID&animation=$gif&reply_markup=$keyboard";
	} elseif (!empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendAnimation?parse_mode=HTML&chat_id=$chatID&animation=$gif&caption=".urlencode($caption)."&reply_markup=$keyboard";
	}
	$result = file_get_contents($url);
	return $result;
}


?>
