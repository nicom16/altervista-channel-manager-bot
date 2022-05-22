<?php
//Tastiera
function inlineKeyboardVid ($buttons, $vid, $chatID) {
	$keyboard = array(
        "inline_keyboard" => $buttons,
    );
    $keyboard = json_encode($keyboard);
    $url = $GLOBALS[website]."/sendVideo?chat_id=$chatID&parse_mode=html&video=$vid&reply_markup=$keyboard";
    $return = file_get_contents($url);
    return $return;
}
?>
