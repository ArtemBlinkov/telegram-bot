<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use TelegramBot\Api\BotApi;
use Templates\FeedbackTemplate;
use TelegramBot\Api\InvalidJsonException;

try {
    // проверяем запрос
    $api = new RequestHandler();
    $data = $api->getData();

    // подключение языкового файла
    $lang = Lang::includeFile('form.php', $data['lang'] ?? LANG);

    // создание шаблона сообщения формы обратной связи
    $template = new FeedbackTemplate($data, $lang);

    // отправляем сообщение в тг
    $bot = new BotApi(APIKEY);
    $message = $bot->sendMessage(ME, $template->get(), 'Markdown');

    // возвращаем ответ
    $api->response(["message" => "ok", "id" => $message->getMessageId()]);

} catch (Exception | InvalidJsonException $e) {
    Logger::exception($e);
}