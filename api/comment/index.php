<?php
//Подключаем header
require_once(__DIR__ . '/../../header.php');

use TelegramBot\Lang;
use TelegramBot\Api\BotApi;
use TelegramBot\CommentTemplate;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

try {

    //Создаём экземпляр бота
    $bot = new BotApi(APIKEY);

    //Получаем данные запроса
    $post = file_get_contents('php://input');

    if ($data = $bot::jsonValidate($post, true))
    {
        //Проверяем, что запрос пришёл с проверенного сайта
        if ($data['pass'] === PASS)
        {
            //Подключение языкового файла
            $lang = Lang::IncludeFile('comment.php', $data['lang'] ?? LANG);

            //Создание шаблона сообщения формы обратной связи
            $template = new CommentTemplate($data, $lang);

            //Добавим клавиатуру
            $keyboard = new InlineKeyboardMarkup([
                [
                    ['text' => $lang['key-add'], 'switch_inline_query_current_chat' => '/answer/' . $data['domain'] . '/' . $data['id'] . '/'],
                    ['text' => $lang['key-delete'], 'callback_data' => '/delete/' . $data['domain'] . '/' . $data['id']]
                ]
            ]);

            //Отправляем сообщение
            $message = $bot->sendMessage(ME, $template->get(), 'Markdown',false, null, $keyboard);

            //TODO:Если сообщение доставлено, сохраним в БД для ождидания ответа

            // Установим код ответа - 200 всё хорошо
            http_response_code(200);

            // Возвращаем ответ
            echo json_encode(["message" => "Ok", "id" => $message->getMessageId()]);
        }
        else
        {
            // Установим код ответа - 400 неверный запрос
            http_response_code(400);

            //TODO: Убрать
            Logger::Debug(PASS);

            // Возвращаем ответ
            echo json_encode(["message" => "Pass wrong!", "pass" => $data['pass']]);
        }
    }

}
catch (Exception $e) {
    Logger::Exception($e);
}