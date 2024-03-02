<?php

use lib\Telegram;

$group_handler = new Telegram(API_KEY);

$getData = $group_handler->getData();
$chat_id = $group_handler->ChatID();
$text = $group_handler->Text();
$type = $getData['message']['chat']['type'];

if ($text == '/group') {
    $content = [
        'chat_id' => $chat_id,
        'text' => 'This is group!'
    ];
    $group_handler->sendMessage($content);
}
