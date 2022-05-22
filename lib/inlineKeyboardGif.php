<?php
//Tastiera
function inlineKeyboardGif ($buttons, $gif, $chatID) {
	$keyboard = array(
        "inline_keyboard" => $buttons,
    );
    $keyboard = json_encode($keyboard);
    $url = $GLOBALS[website]."/sendAnimation?chat_id=$chatID&parse_mode=html&animation=$gif&reply_markup=$keyboard";
    $return = file_get_contents($url);
    return $return;
}
?>
