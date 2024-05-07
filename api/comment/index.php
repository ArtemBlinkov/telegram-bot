<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidJsonException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Templates\CommentTemplate;

try {
    // проверяем запрос
    $api = new RequestHandler();
    $data = $api->getData();

    // подключение языкового файла
    $lang = Lang::includeFile('comment.php', $data['lang'] ?? LANG);

    // создание шаблона сообщения формы обратной связи
    $template = new CommentTemplate($data, $lang);

    // добавляем запись в БД, получаем id
    $db = new CommentRecord();
    $id = $db->createComment($data);

    // добавим клавиатуру
    $keyboard = new InlineKeyboardMarkup([
        [
            ['text' => $lang['key-add'], 'switch_inline_query_current_chat' => '/answer ' . '-:' . $id],
            ['text' => $lang['key-delete'], 'switch_inline_query_current_chat' => '/delete ' . '-:' . $id]
        ]
    ]);

    // отправляем сообщение в тг
    $bot = new BotApi(APIKEY);
    $message = $bot->sendMessage(ME, $template->get(), 'Markdown', false, null, $keyboard);

    // возвращаем ответ
    $api->response(["message" => "ok", "id" => $message->getMessageId()]);

} catch (Exception | InvalidJsonException $e) {
    Logger::exception($e);
}