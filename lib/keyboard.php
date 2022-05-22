<?php
//Tastiera
function keyboard ($buttons, $text, $chatID) {
	$markup = '&reply_markup={"keyboard":['.urlencode($buttons).'],"resize_keyboard":true}';
	$url = $GLOBALS[website].'/sendMessage?chat_id='.$chatID.'&text='.urlencode($text).$markup;
	file_get_contents($url);
}
?>
