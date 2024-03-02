<?php

use lib\Telegram;

include 'src/database/db.php';
include 'lib/Telegram.php';
include 'src/config/bot_config.php';

$webhook = new Telegram(API_KEY);
$chat_id = $webhook->ChatID();
$chat = $webhook->Chat();
$inline_query = $webhook->Inline_Query();
$chat_member = $webhook->Chat_Member();

if ($chat['type'] == 'private') {

    include 'src/handlers/user_handler.php';

    if (in_array($chat_id, ADMINS)) {

        include 'src/handlers/admin_handler.php';

    }
} else if ($chat['type'] == 'supergroup') {

    include 'src/handlers/group_handler.php';

}
if ($inline_query) {

    include "src/handlers/invite_handler.php";

}

if ($chat_member) {

    include "src/handlers/chat_member_handler.php";

}

