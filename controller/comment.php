<?php

use TelegramBot\Api\Types\Update,
    GuzzleHttp\Client;

global $bot;

/**
 * Запуск сценария ответа
 */
$bot->command('answer', function ($message) use ($bot) {

    // получим текст команды, уберём всё кроме id
    $text = $message->getText();
    $text = str_replace('@SitesManagerBot ', '', $text);
    $id = str_replace('/answer -:', '', $text);

    // обновляем запись в бд, меняем статус на wait
    $db = new CommentRecord();

    if ($db->updateComment($id))
    {
        // отправим сообщение о необходимости ввести ответ
        $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['answer']);
    }
    else
    {
        // отправим сообщение об ошибке
        $lang = Lang::IncludeFile('other.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
    }

});

/**
 * Запуск сценария удаления комментария
 */
$bot->command('delete', function ($message) use ($bot) {

    // получим текст команды, уберём всё кроме id
    $text = $message->getText();
    $text = str_replace('@SitesManagerBot ', '', $text);
    $id = str_replace('/delete -:', '', $text);

    // обращаемся к бд, получаем запись
    $db = new CommentRecord();
    $comment = $db->getComment($id);

    // стучимся в первоисточник и удаляем комментарий там
    $client = new Client();
    $response = $client->request('POST', $comment->getDeleteUrl(), [
        'body' => [
            'pass' => PASS,
            'id' => $comment->getCommentId()
        ]
    ]);
    Logger::Debug($response);

    // удаляем запись в бд
    if ($db->deleteComment($id))
    {
        // отправим сообщение об успешном удалении
        $lang = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['delete']);
    }
    else
    {
        // отправим сообщение об ошибке
        $lang = Lang::IncludeFile('other.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
    }

});

/**
 * Обработка ответа на комментарий
 */
$bot->on(function (Update $update) use ($bot) {

    // создаём объект бд
    $db = new CommentRecord();

    // если есть комментарии в бд, ожидающие ответа
    if ($comment = $db->checkComment())
    {
        // получаем данные от телеграмма
        $message = $update->getMessage();

        // подключение языкового файла
        $lang = Lang::IncludeFile('other.php', $message->getFrom()->getLanguageCode());

        // отрабатываем сценарий отмены ответа
        if ($message->getText() == $lang['command_cancel'])
        {
            // откатываем статус комментария
            if ($db->updateComment($comment->getId(), 'NEW'))
            {
                // отправим сообщение об отмене
                $bot->sendMessage($message->getChat()->getId(), $lang['answer_canceled']);
            }
            else
            {
                // отправим сообщение об ошибке
                $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
            }
        }
        else
        {
            // стучимся в первоисточник и отправляем ответ туда
            $client = new Client();
            $response = $client->request('POST', $comment->getAnswerUrl(), [
                'body' => [
                    'pass' => PASS,
                    'ReviewForm' => [
                        'parent' => $comment->getCommentId(),
                        'body' => $message->getText()
                    ]
                ]
            ]);
            Logger::Debug($response);

            // удалить запись из БД
            $db->deleteComment($comment->getId());

            // отправим сообщение об успешном ответе
            $bot->sendMessage($message->getChat()->getId(), $lang['success_comment']);
        }
    }

}, function () {
    return true;
});