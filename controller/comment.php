<?php

global $bot;

$bot->command('answer', function ($message) use ($bot) {

    // получим текст команды
    $text = $message->getText();
    // уберём команду, оставим только параметры
    $param = str_replace('/answer ', '', $text);
    // разобъём строку параметров на массив эелементов
    $ar_param = explode($param, ':');

    Logger::Debug($ar_param);
    //TODO: достать запись из бд
    //TODO: отправить запрос на ресурс

    $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
    $bot->sendMessage($message->getChat()->getId(), $lang['answer']);
});

$bot->command('delete', function ($message) use ($bot) {

    // получим текст команды
    $text = $message->getText();
    // уберём команду, оставим только параметры
    $param = str_replace('/delete ', '', $text);
    // разобъём строку параметров на массив эелементов
    $ar_param = explode($param, ':');

    Logger::Debug($ar_param);
    //TODO: достать запись из бд
    //TODO: отправить запрос на ресурс

    $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
    $bot->sendMessage($message->getChat()->getId(), $lang['delete']);
});