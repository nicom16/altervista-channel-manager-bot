<?php

include_once 'config.php';

$website = "https://api.telegram.org/bot".$botToken;

// Set webhook per Telegram
$path = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME);
$url = $website."/setWebhook?url=https://".$_SERVER['SERVER_NAME'].$path."/index.php";
$result = file_get_contents($url);

if ($result) {
    echo("Webhook settato correttamente! // ");
} else {
    echo("Qualcosa è andato storto! // ");
}

// Creazione link di invito
$url = $website."/createChatInviteLink?chat_id=$channel";
$result = file_get_contents($url);

if (!empty($result)) {
    echo("Link d'invito creato correttamente! // ");
} else {
    echo("Qualcosa è andato storto! // ");
}

// Creazione tabella per il Bot corrente
$conn = mysqli_connect($DBServerName, $DBUsername, null, $DBName);

$tab_query = "CREATE TABLE IF NOT EXISTS $table (
	id INT NOT NULL AUTO_INCREMENT,
	pic_ID VARCHAR(250) NOT NULL UNIQUE,
	spec_ID VARCHAR(250) NOT NULL,
	flag BOOLEAN NOT NULL DEFAULT 0,
	type VARCHAR(3) NOT NULL,
	PRIMARY KEY (id)
	);";
$tab_sql = mysqli_query($conn, $tab_query);

if ($tab_sql) {
    echo("La tabella è stata creata correttamente!");
} else {
    echo(mysqli_error($conn));
}

mysqli_close($conn);

?>
