<?php

use GuzzleHttp\Client;
use TelegramBot\Api\Types\Update;

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

    if ($db->updateComment($id)) {
        // отправим сообщение о необходимости ввести ответ
        $lang = Lang::includeFile('command.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['answer']);
    } else {
        // отправим сообщение об ошибке
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());
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

    // обращаемся к бд, получаем запись по id
    $db = new CommentRecord();

    if ($comment = $db->getComment($id)) {
        // если запись есть в БД, то стучимся в первоисточник и удаляем комментарий там
        $client = new Client();
        $response = $client->post($comment->getDeleteUrl(), [
            'pass' => PASS,
            'id' => $comment->getCommentId()
        ]);

        // форматируем ответ
        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['status'] == 'ok') {
            // если запись успешно удалена в первоисточнике, удаляем в локальной БД
            $db->deleteComment($id);

            // отправим сообщение об успешном удалении
            $lang = Lang::includeFile('command.php', $message->getFrom()->getLanguageCode());
            $bot->sendMessage($message->getChat()->getId(), $lang['delete']);
        } else {
            Logger::Debug($response);
            // отправим сообщение об ошибке
            $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());
            $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
        }
    } else {
        // отправим сообщение об ошибке
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());
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
    if ($comment = $db->checkComment()) {
        // получаем данные сообщения
        $message = $update->getMessage();

        // подключение языкового файла
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());

        // отрабатываем сценарий отмены ответа
        if ($message->getText() == $lang['command_cancel']) {
            // откатываем статус комментария
            if ($db->updateComment($comment->getId(), 'NEW')) {
                // отправим сообщение об успешной отмене
                $bot->sendMessage($message->getChat()->getId(), $lang['answer_canceled']);
            } else {
                // иначе отправим сообщение об ошибке
                $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
            }
        } else {
            // стучимся в первоисточник и отправляем ответ туда
            $client = new Client();
            $response = $client->post($comment->getAnswerUrl(), [
                'pass' => PASS,
                'parent' => $comment->getCommentId(),
                'body' => $message->getText()
            ]);

            // форматируем ответ
            $response = json_decode($response->getBody()->getContents(), true);

            if ($response['status'] == 'ok') {
                // если ответ успешно доставлен в первоисточник, то удалить запись из локальной БД
                $db->deleteComment($comment->getId());

                // отправим сообщение об успешном ответе
                $bot->sendMessage($message->getChat()->getId(), $lang['success_comment']);
            } else {
                Logger::debug($response);
                // отправим сообщение об ошибке
                $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
            }
        }
    }
}, function () {
    return true;
});