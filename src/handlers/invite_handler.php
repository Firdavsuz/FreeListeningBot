<?php

use lib\Telegram;

$invite = new Telegram(API_KEY);

$getData = $invite->getData();
$chat_id = $invite->ChatID();
$inline_query = $invite->Inline_Query();

if ($inline_query['query']) {

    $status = $invite->getChatMember([
        'chat_id' => '@IELTS_Score_up',
        'user_id' => $inline_query['from']['id'],
    ])['result']['status'];

    if ($status == 'member' or $status == 'administrator' or $status == 'creator') {

        $result[] = [
            'type' => 'photo',
            'id' => uniqid(true),
            'title' => 'invite post',
            'thumbnail_url' => 'https://english.ceofox.uz/src/images/main.jpg',
            'photo_url' => 'https://english.ceofox.uz/src/images/main.jpg',
            'photo_width' => 1080,
            'photo_height' => 1080,
            'caption' => "Bepul FULL IELTS kurs.

Qatnashishingizni tavsiya qilaman 👇",
            'description' => 'nadir',
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        ['text' => '⚡️ Kursga qatnashish ⚡️', 'url' => "https://t.me/" . BOT_USER . '?start=' . $chat_id]
                    ],
                ]
            ]
        ];

        $content['switch_pm_parameter'] = "start";
        $content['switch_pm_text'] = "Ulashish uchun rasm ustiga bosing";
        $content['results'] = json_encode($result);

    } else {
        $content['switch_pm_parameter'] = "join";
        $content['switch_pm_text'] = "Kanalga obuna bo'ling";
    }
    $content = [
        'inline_query_id' => $inline_query['id'],
        'cache_time' => 10,
        'results' => $content['results'],
        'switch_pm_parameter' => $content['switch_pm_parameter'],
        'switch_pm_text' => $content['switch_pm_text'],
    ];
    $invite->answerInlineQuery($content);

}