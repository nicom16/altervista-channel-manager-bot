<?php
// Invia video
function sendVideo ($chatID, $vid, $caption, $keys) {
	if (empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendVideo?parse_mode=HTML&chat_id=$chatID&video=$vid";
	} elseif (!empty ($caption) && empty ($keys)) {
		$url = $GLOBALS[website]."/sendVideo?parse_mode=HTML&chat_id=$chatID&video=$vid&caption=".urlencode($caption);
	} elseif (empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendVideo?parse_mode=HTML&chat_id=$chatID&video=$vid&reply_markup=$keyboard";
	} elseif (!empty ($caption) && !empty ($keys)) {
		$keyboard = array (
			"inline_keyboard" => $keys,
		);
		$keyboard = json_encode($keyboard);	
		$url = $GLOBALS[website]."/sendVideo?parse_mode=HTML&chat_id=$chatID&video=$vid&caption=".urlencode($caption)."&reply_markup=$keyboard";
	}
	$result = file_get_contents($url);
	return $result;
}

?>
