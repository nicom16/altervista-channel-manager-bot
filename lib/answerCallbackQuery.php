<?php
//Invia messaggio
function answerCallbackQuery ($id, $text, $alert){
    if (!empty ($text)) {
        $url = $GLOBALS[website]."/answerCallbackQuery?callback_query_id=$id&text=".urlencode($text)."&show_alert=$alert";
    } else {
        $url = $GLOBALS[website]."/answerCallbackQuery?callback_query_id=$id";
    }
    $result = file_get_contents($url);
    return $result;
}
?>
