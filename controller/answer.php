<?php

global $bot;

$bot->command('answer', function ($message) use ($bot) {
    /*$lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
    $bot->sendMessage($message->getChat()->getId(), $lang['start']);*/
});