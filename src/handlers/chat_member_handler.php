<?php

use lib\Telegram;

$chat_member = new Telegram(API_KEY);

$getData = $chat_member->getData();
$chat_id = $chat_member->ChatID();
$new_chat_member = $getData['chat_member']['new_chat_member'];
$full_name = ($chat_member->LastName() != null) ? $chat_member->FirstName() . ' ' . $chat_member->LastName() : $chat_member->FirstName();

if (in_array($getData['chat_member']['chat']['id'], CHANNELS)) {

    if ($new_chat_member['status'] == 'member' or $new_chat_member['status'] == 'administrator' or $new_chat_member['status'] == 'creator') {
        $option = [
            [
                $chat_member->buildInlineKeyboardButton('📤 Taklif qilish', '', '', '', 'share'),
            ]
        ];

        $keyboard = $chat_member->buildInlineKeyBoard($option);

        $text = "Assalomu alaykum, <a href='tg://user?id=$chat_id'>$full_name</a> !

<b>❗️Diqqat bilan o’qing!</b>

Bot sizga taqdim etgan referral linkni atigi <b>5 nafar</b> ingliz tili o'rganayotgan do'stingizga yuboring va bot sizga avtomatik tarzda kurs uchun link beradi.

<b>Quyidagi tugmani bosing va taklif qilishni boshlang 👇</b>";

        $content = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ];

        $chat_member->sendMessage($content);

        update('referrals', $chat_id, 'isjoin', '1');
    }

    if ($new_chat_member['status'] == 'left') {
        $isJoin = $chat_member->getJoinRequest($chat_id, CHANNELS);
        update('referrals', $chat_id, 'isjoin', '0');
        if (!$isJoin['ok']) {
            $content = ['chat_id' => $chat_id, 'text' => "Botdan to'liq foydalanish uchun kanallarga obuna bo'ling\n\n" . $isJoin['text'], 'reply_markup' => $isJoin['button'], 'disable_web_page_preview' => true, 'parse_mode' => 'MARKDOWN'];
            $chat_member->sendMessage($content);
            exit();
        }
    }

}