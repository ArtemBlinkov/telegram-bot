<?php

require_once(__DIR__ . '/../../../vendor/autoload.php');

use TelegramBot\Api\InvalidJsonException;

try {
    // проверяем запрос
    $api = new RequestHandler();
    $data = $api->getData();

    // получаем запись из бд
    $db = new CommentRecord();

    if ($comment = $db->getCommentByParent($data['id'])) {
        // удаляем запись из бд
        $db->deleteComment($comment->getId());

        // возвращаем ответ
        $api->response(["message" => "ok"]);
    } else {
        $api->badRequest(["message" => "Review don't found"], 404);
    }
} catch (Exception | InvalidJsonException $e) {
    Logger::exception($e);
}