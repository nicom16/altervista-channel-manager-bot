<?php
//Tastiera
function inlineKeyboardPic ($buttons, $pic, $chatID) {
	$keyboard = array(
        "inline_keyboard" => $buttons,
    );
    $keyboard = json_encode($keyboard);
    $url = $GLOBALS[website]."/sendPhoto?chat_id=$chatID&parse_mode=html&photo=$pic&reply_markup=$keyboard";
    $return = file_get_contents($url);
    return $return;
}
?>
