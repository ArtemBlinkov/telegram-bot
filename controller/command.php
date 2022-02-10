<?php

global $bot, $lang;

// Команда start
$bot->command('start', function ($message) use ($bot, $lang) {
    $bot->sendMessage($message->getChat()->getId(), $lang['start']);
});

// Команда help
$bot->command('help', function ($message) use ($bot, $lang) {
    $bot->sendMessage($message->getChat()->getId(), $lang['help']);
});