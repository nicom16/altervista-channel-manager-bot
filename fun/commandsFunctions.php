<?php

// Inserisci un elemento nel database
function insert($chatID, $id, $id2, $type) {
    GLOBAL $table;

    $query0 = "SELECT id FROM $table WHERE pic_ID ='$id' AND type = '$type';";
    $sql0 = mysqli_query($GLOBALS[conn], $query0);

    if (mysqli_num_rows($sql0) === 0) {
        $query1 = "INSERT INTO $table (pic_ID, spec_ID, type) VALUES ('$id', '$id2', '$type');";
		if (!mysqli_query($GLOBALS[conn], $query1)) {
			$error = "Opss, qualcosa è andato storto! L'immagine non è stata inserita nel database";
			sendMessage ($chatID, $error);
		} else {
			$confirmation = "L'immagine è stata inserita con successo nel database";
			sendMessage($chatID, $confirmation);
		}
    } else {
        sendMessage($chatID, "L'immagine è già presente nel database");
    }

    mysqli_close($GLOBALS[conn]);
}

// Invia un elemento a caso sul canale
function sendToChannel($chatID, $channel, $cap) {
    GLOBAL $table, $send;

    $quer = "SELECT * FROM $table WHERE flag = TRUE ORDER BY RAND() LIMIT 1;";
    $flag = mysqli_query ($GLOBALS[conn], $quer, MYSQLI_USE_RESULT);
    $row = mysqli_fetch_array ($flag);
    mysqli_free_result($flag);

    if (empty ($row)) {
        $query0 = "SELECT * FROM $table ORDER BY RAND() LIMIT 1;";
        $sql0 = mysqli_query($GLOBALS[conn], $query0, MYSQLI_USE_RESULT);
        $row = mysqli_fetch_array ($sql0);
        mysqli_free_result($sql0);

        if (empty($row)) {
            sendMessage ($chatID, "Il database è vuoto!");
        } 
    }
    
    $picID_unique = $row["pic_ID"];
    $picID = $row["spec_ID"]; 
    $type = $row["type"];

    switch ($type) {
    case "pic":
        sendPhoto($channel, $picID, $cap, null);
        break;
    case "gif":
        sendGif($channel, $picID, $cap, null);
        break;
    case "vid":
        sendVideo($channel, $picID, $cap, null);
        break;
    }

    $query = "DELETE FROM $table WHERE pic_ID = '$picID_unique';";
    $sql = mysqli_query($GLOBALS[conn], $query, MYSQLI_USE_RESULT);
    $query = "SET @num = 0;";
    $sql = mysqli_query($GLOBALS[conn], $query, MYSQLI_USE_RESULT);
    $query = "UPDATE $table SET id = @num := (@num+1);";
    $sql = mysqli_query($GLOBALS[conn], $query, MYSQLI_USE_RESULT);
    $query = "ALTER TABLE $table AUTO_INCREMENT =1;";
    $sql = mysqli_query($GLOBALS[conn], $query, MYSQLI_USE_RESULT);

    if ($sql) {
        sendMessage($chatID, "Elemento inviato sul canale ed eliminato dal database");
    } else {
        sendMessage($chatID, "Opss, qualcosa è andato storto! L'elemento non è stato eliminato dal database");
    }

    if (empty($send) || $send == 1) {
        mysqli_close($GLOBALS[conn]);
    }
}

// Elimina un elemento selezionato dal database
function deleteFromDB($chatID, $quotation) {
    GLOBAL $table;

	if (!empty ($quotation)) {
        $picID = $quotation['photo']['1']['file_unique_id'];
        $gifID = $quotation['animation']['file_unique_id'];
        $vidID = $quotation['video']['file_unique_id'];
        
        if (!empty ($picID)) {
            $elem = $picID;
        } elseif (!empty ($gifID)) {
            $elem = $gifID;
        } elseif (!empty ($vidID)) {
            $elem = $vidID;
        }

        $query = "DELETE FROM $table WHERE pic_ID = '$elem';";
        $query .= "SET @num = 0;";
    	$query .= "UPDATE $table SET id = @num := (@num+1);";
    	$query .= "ALTER TABLE $table AUTO_INCREMENT =1;";

        if (!mysqli_multi_query ($GLOBALS[conn], $query)) {
			$error = "Opss, qualcosa è andato storto! L'immagine non è stata eliminata dal database";
			sendMessage($chatID, $error);
		} else {
			$confirmation = "L'immagine è stata eliminata dal database";
			sendMessage($chatID, $confirmation);
		}
	} else {
		$string = "Devi rispondere al messaggio contenente l'immagine che vuoi eliminare dal database!";
		sendMessage($chatID, $string);
	}
    mysqli_close($GLOBALS[conn]);
}

// Conta gli elementi presenti nel database
function countDB($chatID) {
    GLOBAL $table;

	$query0 = "SELECT id FROM $table;";
    $sql0 = mysqli_query($GLOBALS[conn], $query0);
    $count = mysqli_num_rows($sql0);
    sendMessage($chatID, "Elementi residui: ".$count);
}

// Flagga (-> dai priorità) un elemento del database
function flag ($chatID, $quotation) {
    GLOBAL $table;

    if (!empty ($quotation)) {
        $picID = $quotation['photo']['1']['file_unique_id'];
        $gifID = $quotation['animation']['file_unique_id'];
        $vidID = $quotation['video']['file_unique_id'];
        
        if (!empty ($picID)) {
            $elem = $picID;
        } elseif (!empty ($gifID)) {
            $elem = $gifID;
        } elseif (!empty ($vidID)) {
            $elem = $vidID;
        }

        $sql = "UPDATE $table SET flag = TRUE WHERE pic_ID = '$elem';";
        mysqli_query ($GLOBALS[conn], $sql);
        sendMessage ($chatID, "Il messaggio selezionato è stato flaggato!");
    } else {
        sendMessage ($chatID, "Devi rispondere al messaggio che vuoi flaggare!");
    }
}

// Unflagga un elemento del database
function unflag ($chatID, $quotation) {
    GLOBAL $table;

    if (!empty ($quotation)) {
        $picID = $quotation['photo']['1']['file_unique_id'];
        $gifID = $quotation['animation']['file_unique_id'];
        $vidID = $quotation['video']['file_unique_id'];
        
        if (!empty ($picID)) {
            $elem = $picID;
        } elseif (!empty ($gifID)) {
            $elem = $gifID;
        } elseif (!empty ($vidID)) {
            $elem = $vidID;
        }

        $sql = "UPDATE $table SET flag = FALSE WHERE pic_ID = '$elem';";
        mysqli_query ($GLOBALS[conn], $sql);
        sendMessage ($chatID, "Il messaggio selezionato è stato unflaggato!");
    } else {
        sendMessage ($chatID, "Devi rispondere al messaggio che vuoi unflaggare!");
    }
}

?>
