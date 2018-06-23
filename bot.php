<?php

/*
 * @author @SpEcHIDe
 */

require_once __DIR__ . "/kyle2142_PHPBot.php";
require_once __DIR__ . "/ZedgeScrapper.php";
require_once __DIR__ . "/config.php";

$telegram = new kyle2142\PHPBot($TG_BOT_TOKEN);

$content = file_get_contents('php://input');
$update = json_decode($content, true);
// do stuff with $update:

// /start message below
if(isset($update['message']['text']) and $update['message']['text'] === "/start"){
    $msg_id = $update['message']['message_id'];
    $chat_id = $update['message']['chat']['id'];
    $name = $update['message']['from']['first_name'];
    $telegram->sendMessage(
        $chat_id,
        "The UseLess Bot. Subscribe @ZedgeImages to know more! \r\n I'm using [this](https://github.com/Kyle2142/PHPBot) awesome library.",
        ['reply_to_message_id' => $msg_id, 'disable_web_page_preview' => True]
    );
}

if(isset($update["inline_query"]) and $update["inline_query"] != ""){
    $query = $update["inline_query"]["query"];
    $results = json_encode(GetImgUrl($query));
    $content = array(
        'inline_query_id' => $update["inline_query"]["id"],
        'cache_time' => "0",
        'is_personal' => "True",
        'results' => $results
    );
    $reply = $telegram->api->answerInlineQuery($content);
}
