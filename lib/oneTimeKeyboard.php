<?php
//Tastiera
function oneTimeKeyboard ($buttons, $text, $chatID) {
	$markup = '&reply_markup={"keyboard":['.urlencode($buttons).'],"resize_keyboard":true,"one_time_keyboard":true}';
    $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatID.'&parse_mode=html&text='.urlencode($text).$markup;
	$result = file_get_contents($url);
    return $result;
}
?>
