<?php

global $bot;

$bot->command('start', function ($message) use ($bot) {
    $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
    $bot->sendMessage($message->getChat()->getId(), $lang['start']);
});

$bot->command('help', function ($message) use ($bot) {
    $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
    $bot->sendMessage($message->getChat()->getId(), $lang['help']);
});