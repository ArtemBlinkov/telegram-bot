<?php

use GuzzleHttp\Client;
use TelegramBot\Api\Types\Update;

global $bot;

/**
 * Запуск сценария ответа
 * Смена статуса сообщения на WAIT
 */
$bot->command('answer', function ($message) use ($bot) {
    $text = $message->getText();
    $id = str_replace(['/answer -:', '@SitesManagerBot '], '', $text);

    $db = new CommentRecord();

    if ($db->updateComment($id)) {
        $lang = Lang::includeFile('command.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['answer']);
    } else {
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
    }
});

/**
 * Запуск сценария удаления комментария
 */
$bot->command('delete', function ($message) use ($bot) {
    $text = $message->getText();
    $id = str_replace(['/delete -:', '@SitesManagerBot '], '', $text);

    $error = false;
    $db = new CommentRecord();

    if ($comment = $db->getComment($id)) {
        try {
            $client = new Client();
            $response = $client->delete($comment->getDeleteUrl(), [
                'json' => [
                    'pass' => PASS,
                    'comment_id' => $comment->getCommentId()
                ]
            ]);
        } catch (Throwable $e) {
            $error = 'error_comment';
            Logger::error($e->getMessage());
        }

        if (isset($response)) {
            $response = json_decode($response->getBody()->getContents(), true);

            if ($response['status'] == 'ok') {
                $db->deleteComment($id);

                $lang = Lang::includeFile('command.php', $message->getFrom()->getLanguageCode());
                $bot->sendMessage($message->getChat()->getId(), $lang['delete']);
            } else {
                $error = 'error_comment';
                Logger::error($response);
            }
        }
    } else {
        $error = 'error_database';
    }

    if ($error) {
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());
        $bot->sendMessage($message->getChat()->getId(), $lang[$error]);
    }
});

/**
 * Обработка ответа на комментарий
 */
$bot->on(function (Update $update) use ($bot) {
    $db = new CommentRecord();

    if ($comment = $db->checkComment()) {

        $message = $update->getMessage();
        $lang = Lang::includeFile('other.php', $message->getFrom()->getLanguageCode());

        if ($message->getText() == $lang['command_cancel']) {
            if ($db->updateComment($comment->getId(), 'NEW')) {
                $bot->sendMessage($message->getChat()->getId(), $lang['answer_canceled']);
            } else {
                $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
            }
        } else {
            try {
                $client = new Client();
                $response = $client->post($comment->getAnswerUrl(), [
                    'json' => [
                        'pass' => PASS,
                        'comment_id' => $comment->getCommentId(),
                        'message' => $message->getText()
                    ]
                ]);
            } catch (Throwable $e) {
                Logger::debug($e->getMessage());
                $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
            }

            if (isset($response)) {
                $response = json_decode($response->getBody()->getContents(), true);

                if ($response['status'] == 'ok') {
                    $db->deleteComment($comment->getId());

                    $bot->sendMessage($message->getChat()->getId(), $lang['success_comment']);
                } else {
                    Logger::debug($response);
                    $bot->sendMessage($message->getChat()->getId(), $lang['error_comment']);
                }
            }
        }
    }
}, function () {
    return true;
});
