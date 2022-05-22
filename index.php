<?php

// Imports
foreach (glob('./lib/*.php') as $fun) {
	include_once $fun;
}
foreach (glob('./fun/*.php') as $fun) {
	include_once $fun;
}
include_once 'config.php';

// General variables
$website = "https://api.telegram.org/bot".$botToken;
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);
$scheduling = file_get_contents("./scheduling.txt");
$stopRequests = file_get_contents("./stop.txt");

// Callback query
$cb_call = $update['callback_query']['data'];
$cb_id = $update['callback_query']['from']['id'];
$cb_mess = $update['callback_query']['message']['message_id'];
$query_id = $update['callback_query']['id'];

// Incoming message variables
$message = $update['message']['text'];
$chatID = $update['message']['from']['id'];
$telegramUsername = $update['message']['from']['username'];
$pic = $update['message']['photo']['1']['file_unique_id'];
$picID = $update['message']['photo']['1']['file_id'];
$gif = $update['message']['animation']['file_unique_id'];
$gifID = $update['message']['animation']['file_id'];
$vid = $update['message']['video']['file_unique_id'];
$vidID = $update['message']['video']['file_id'];
$quote = $update['message']['reply_to_message'];
$chat_join_request_id = $update['chat_join_request']['from']['id'];

// Db connection
$conn = mysqli_connect($DBServerName, $DBUsername, null, $DBName);

// Keyboard
$keys = '["Invia"],["Conta elementi"],["Programma orario"],["Disattiva richieste"]';
if (!empty($stopRequests)) {
    $keys = '["Invia"],["Conta elementi"],["Programma orario"],["Attiva richieste"]';
}

$keysDisattiva = '["Invia"],["Conta elementi"],["Programma orario"],["Disattiva richieste"]';
$keysAttiva = '["Invia"],["Conta elementi"],["Programma orario"],["Attiva richieste"]';

// Main
if (!empty($cb_call)) {
    switch ($cb_call) {
        case "scheduling":
            answerCallbackQuery($query_id, null, FALSE);
            $put = file_put_contents("./scheduling.txt", "I'm scheduling!");
            sendMessage($cb_id, "Invia l'orario che vuoi aggiungere");
            break;
        default:
            answerCallbackQuery($query_id, null, FALSE);
            deleteCron($cb_call, $cb_id);
            break;
    }
} elseif ($_GET["counter"]) {
    countDB($user_id);
} elseif ($_GET["send"]) {
    $send = $_GET["send"];

    if ($send !== 1) {
        for ($send; $send > 0; $send--) {
            sendToChannel(null, $channel, $caption);
        }
    } else {
        sendToChannel(null, $channel, $caption);
    }

    return "Sended!";
} elseif (!empty($chat_join_request_id) && empty($stopRequests)) {
    // Automatically decline arabic accounts' join requests
    $is_arabic = preg_match('/\p{Arabic}/u', $update['chat_join_request']['from']['first_name']);
    $is_arabic2 = preg_match('/\p{Arabic}/u', $update['chat_join_request']['from']['last_name']);
    
    if ($is_arabic || $is_arabic2) {
        declineRequest($channel, $chat_join_request_id);
    } else {
        approveRequest($channel, $chat_join_request_id);
    }
} elseif ($chatID == $user_id) {
    if (!empty($pic)) {
		insert($chatID, $pic, $picID, "pic");
	} elseif (!empty($gif)) {
		insert($chatID, $gif, $gifID, "gif");
	} elseif (!empty($vid)) {
		insert($chatID, $vid, $vidID, "vid");
    } elseif (!empty($scheduling)) {
        $path = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME);
        $bot_page = "https://".$_SERVER['SERVER_NAME'].$path."/index.php";
        addCron($bot, $bot_page, $message, $chatID);
    } else {
		switch ($message) {
        	case "/start":
                keyboard($keys, "Ciao", $chatID);
                break;
			case "Invia":
				sendToChannel($chatID, $channel, $caption);
				break;
			case "Elimina":
            case "elimina":
				deleteFromDB($chatID, $quote);
				break;
            case "Flag":
            case "flag":
                flag($chatID, $quote);
                break;
            case "Unflag":
            case "unflag":
                unflag($chatID, $quote);
                break;
            case "Conta elementi":    
            	countDB($chatID);
                break;
            case "Programma orario":
                listCron($bot, $chatID);
                break;
            case "Attiva richieste":
                unlink("./stop.txt");
                keyboard($keysDisattiva, "L'accettazione automatica delle richieste è stata attivata.", $chatID);
                break;
            case "Disattiva richieste":
                file_put_contents("./stop.txt", "Stop it!");
                keyboard($keysAttiva, "L'accettazione automatica delle richieste è stata disattivata.", $chatID);
                break;
            default:
                keyboard($keys, "Il comando inserito non è supportato!", $chatID);
				break;	
		} 		
	}
} else {
	sendMessage($chatID, "Non sei autorizzato ad utilizzare questo bot!");
}

?>
