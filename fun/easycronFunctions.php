<?php

function addCron($bot, $bot_page, $message, $chatID) {
    GLOBAL $cron_token, $heroku_link;
    
    $cron_add = "https://www.easycron.com/rest/add?token=$cron_token&";

    $multiple = strpos($message, "=");
    
    if ($multiple !== FALSE) {
        $send = substr($message, $multiple + 1);
        $message = substr($message, 0, $multiple);
    } else {
        $send = 1;
    }
    
    if (strlen($message) < 5 && strlen($message) > 2) {
        if (strlen($message) === 3) {
        	$hour = substr($message, 0, 1);
            if ($hour > 7) {
            	$message = "0".$message;
            	$hour = substr($message, 0, 2);
            	$min = substr($message, 2, 2);
            } else {
            	$hour = null;
            }
        } else {
            $hour = substr($message, 0, 2);
            if ($hour < 24) {
            	$min = substr($message, 2, 2);
            } else {
            	$hour = null;
            }
        }
		
        if (!empty($hour)) {
            $post_url = "$heroku_link?bot=$bot&bot_page=$bot_page&send=$send";
            $post_url = urlencode($post_url);
            $time = "$min $hour * * *";
            $time = urlencode($time);

            $request = $cron_add."url=$post_url&cron_expression=$time&cron_job_name=$bot";
            $res = file_get_contents($request);
            $res = json_decode($res, TRUE);

            if ($res["status"] === "success") {
                sendMessage($chatID, "L'orario è stato aggiunto correttamente!");
            } else {
                sendMessage($chatID, "No eh: ".$result["error"]["message"]);
            }
        } else {
        	sendMessage($chatID, "Puoi inserire solo orari compresi fra le 8 e le 24!");
        }
        unlink("./scheduling.txt");
    } else {
        sendMessage($chatID, "Il valore inserito non è valido! Devi inviare un orario nel formato hhmm oppure hmm");
    }
}

function listCron($bot, $chatID) {
    GLOBAL $cron_token;
    
    $cron_list = "https://www.easycron.com/rest/list?token=$cron_token&size=50";
    $result = file_get_contents($cron_list);
    $result = json_decode($result, TRUE);
    $max_ind = count($result["cron_jobs"]);
    
    for ($it = 0; $it < $max_ind; $it++) {
        $value = preg_grep("/^$bot.*?$/i", $result["cron_jobs"][$it]);
        if (!empty($value)) {
            $to_list[] = $it;
        }
    }
    
    foreach ($to_list as $index) {
        $cron_exp = $result["cron_jobs"][$index]["cron_expression"];
        $min = substr($cron_exp, 0, 2);
        $hour = substr($cron_exp, 3, 2);
	$send = strrchr($result["cron_jobs"][$index]["url"], "=");
	
	$schedule[$result["cron_jobs"][$index]["cron_job_id"]] = "$hour:$min$send"; 
    }

    if (!empty($schedule)) {
        foreach ($schedule as $key => $val) {
            $tasti[] = array(array("text" => "$val", "callback_data" => "$key"),);
        }
        $tasti[] = array(array("text" => urlencode("Aggiungi orario"), "callback_data" => "scheduling"),);
        inlineKeyboard($tasti, "Questi sono gli orari di invio attualmente programmati. Clicca su uno di essi per eliminarlo, premi sul bottone Aggiungi orario per aggiungerne un altro", $chatID);
    } else {
        $button[] = array(array("text" => urlencode("Aggiungi orario"),"callback_data" => "scheduling"),);
        inlineKeyboard($button, "Premi sul bottone per aggiungere un nuovo orario di invio", $chatID);
    }
}

function deleteCron($cron, $chatID) {
    GLOBAL $cron_token;

    $cron_del = "https://www.easycron.com/rest/delete?token=$cron_token&id=$cron";
    $return = file_get_contents($cron_del);
    $return = json_decode($return, TRUE);
    
    if ($return["status"] === "success") {
        sendMessage($chatID, "Orario eliminato con successo!");
    } else {
        sendMessage($chatID, "Nope: ".$return["error"]["message"]);
    }
}

?>