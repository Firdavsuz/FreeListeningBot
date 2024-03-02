<?php

use lib\Telegram;

$admin = new Telegram(API_KEY);

$getData = $admin->getData();
$chat_id = $admin->ChatID();
$text = $admin->Text();

if ($text == '/admin') {

    $option = [
        [
            $admin->buildKeyboardButton('🗣 Xabar yuborish'),
        ],
        [
            $admin->buildKeyboardButton('📢 Kanallar ro\'yxati'),
            $admin->buildKeyboardButton('👥 Adminlar ro\'yxati'),
        ],
        [
            $admin->buildKeyboardButton('📈 Bot a\'zolari'),
        ]
    ];

    $keyboard = $admin->buildKeyBoard($option, true, true);

    $content = [
        'chat_id' => $chat_id,
        'text' => 'admin is active!',
        'reply_markup' => $keyboard
    ];
    $admin->sendMessage($content);
}

if ($text == '📢 Kanallar ro\'yxati') {
    $channels = channelsArray();
    $button = [];
    foreach ($channels as $channel) {
        $getChat = $admin->getChat(['chat_id' => $channel])['result'];
        $button[] = ['text' => $getChat['title'], 'callback_data' => $getChat['id']];
    }
    $button[] = ['text' => '➕ Kanal qo\'shish', 'callback_data' => 'addChannel'];
    $keyboard = $admin->buildInlineKeyBoard(array_chunk($button, 1));
    $content = [
        'chat_id' => $chat_id,
        'text' => "Barcha kanallar ro'yxatini ko'rishingiz mumkin:",
        'reply_markup' => $keyboard
    ];
    $admin->sendMessage($content);
} else if ($text == '👥 Adminlar ro\'yxati') {
    $admins = adminsArray();
    $button = [];
    foreach ($admins as $adminChatID) {
        $getChat = $admin->getChat(['chat_id' => $adminChatID])['result'];
        $full_name = ($getChat['last_name'] ? $getChat['first_name'] . ' ' . $getChat['last_name'] : $getChat['first_name']);
        $button[] = ['text' => $full_name, 'url' => 'tg://user?id=' . $getChat['id']];
    }
    $button[] = ['text' => '➕ Admin qo\'shish', 'callback_data' => 'addAdmin'];
    $button[] = ['text' => '➖ Adminlikdan olish', 'callback_data' => 'removeAdmin']; 
    $keyboard = $admin->buildInlineKeyBoard(array_chunk($button, 1));
    $content = [
        'chat_id' => $chat_id,
        'text' => "Barcha adminlar: ",
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    ];
    $admin->sendMessage($content);
}