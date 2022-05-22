<?php
//Tastiera
function inlineKeyboard ($buttons, $text, $chatID) {
	$keyboard = array(
        "inline_keyboard" => $buttons,
    );
    $keyboard = json_encode($keyboard);
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatID&parse_mode=html&text=".urlencode($text)."&reply_markup=$keyboard";
    $return = file_get_contents($url);
    return $return;
}
?>
