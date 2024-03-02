<?php

use lib\Telegram;

$user_handler = new Telegram(API_KEY);

$getData = $user_handler->getData();

$chat_id = $user_handler->ChatID();
$text = $user_handler->Text();
$callback_data = $user_handler->Callback_Data();
$data = $user_handler->Callback_Data();
$callback_id = $user_handler->Callback_ID();
$message_id = $user_handler->MessageID();
$full_name = ($user_handler->LastName() != null) ? $user_handler->FirstName() . ' ' . $user_handler->LastName() : $user_handler->FirstName();

if ($text == '/start join') {

    $content = [
        'chat_id' => $chat_id,
        'text' => "Botdan to'liq foydalanish uchun kanalga obuna bo'ling: @IELTS_Score_up"
    ];

    $user_handler->sendMessage($content);

} else if (strpos(strtolower($text), '/start') === 0) {

    $ref_user_id = substr($text, 7);
    addUser($chat_id, $ref_user_id);

    if ($ref_user_id != $chat_id) {

        $count = getCountUserRef($ref_user_id);

        $isJoin = $user_handler->getJoinRequest($chat_id, CHANNELS);

        if (!$isJoin['ok']) {
            $content = ['chat_id' => $chat_id, 'text' => "Botdan to'liq foydalanish uchun kanallarga obuna bo'ling\n\n" . $isJoin['text'], 'reply_markup' => $isJoin['button'], 'disable_web_page_preview' => true, 'parse_mode' => 'MARKDOWN'];
            $user_handler->sendMessage($content);
            exit();
        }

        $text = "<b>✅ <a href='tg://user?id=$chat_id'>$full_name</a> taklif havolangiz orqali qo'shildi!</b>\n\nJami takliflaringiz soni: <b>{$count} ta</b>";

        $content = [
            'chat_id' => $ref_user_id,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        $user_handler->sendMessage($content);

        if ($count == 5) {
            $content = [
                'chat_id' => PRIVATE_GROUP,
                'name' => $ref_user_id,
                'member_limit' => 1
            ];
            $link = $user_handler->createChatInviteLink($content);
            $user_handler->sendMessage(['chat_id' => $ref_user_id, 'text' => "Tabriklaymiz ! Guruhga qo'shilishingiz uchun havola: {$link['result']['invite_link']}", 'parse_mode' => 'HTML']);

        }

    }

    $text = "Assalomu alaykum, <a href='tg://user?id=$chat_id'>$full_name</a> !

<b>❗️Diqqat bilan o’qing!</b>

Bot sizga taqdim etgan referral linkni atigi <b>5 nafar</b> ingliz tili o'rganayotgan do'stingizga yuboring va bot sizga avtomatik tarzda kurs uchun link beradi.

<b>Quyidagi tugmani bosing va taklif qilishni boshlang 👇</b>";

    $option = [
        [
            $user_handler->buildInlineKeyboardButton('📤 Taklif qilish', '', '', '', 'share'),
        ]
    ];

    $keyboard = $user_handler->buildInlineKeyBoard($option);

    $content = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    ];

    $user_handler->sendMessage($content);
}


/*if ($getData) {
    $user_handler->sendMessage(['chat_id' => $chat_id, 'text' => json_encode($getData, JSON_PRETTY_PRINT)]);
}*/