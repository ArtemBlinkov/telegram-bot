<?php

use TelegramBot\Api\Types\Update;

global $bot, $lang;

// Команда start
$bot->command('start', function ($message) use ($bot, $lang) {
    $bot->sendMessage($message->getChat()->getId(), $lang['start']);
});

// Команда help
$bot->command('help', function ($message) use ($bot, $lang) {
    $bot->sendMessage($message->getChat()->getId(), $lang['help']);
});

// Обработчик текстового сообщения
$bot->on(function (Update $update) use ($bot) {
    $message = $update->getMessage();
    $bot->sendMessage($message->getChat()->getId(), 'Your message: ' . $message->getText());
}, function () {
    return true;
});