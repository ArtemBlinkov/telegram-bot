<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once(__DIR__ . '/../../vendor/autoload.php');

use TelegramBot\Api\BotApi,
    Templates\FeedbackTemplate;

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
            $lang = Lang::IncludeFile('form.php', $data['lang'] ?? LANG);

            //Создание шаблона сообщения формы обратной связи
            $template = new FeedbackTemplate($data, $lang);

            //Отправляем сообщение
            $message = $bot->sendMessage(ME, $template->get(), 'Markdown');

            // Установим код ответа - 200 всё хорошо
            http_response_code(200);

            // Возвращаем ответ
            echo json_encode(["message" => "Ok", "id" => $message->getMessageId()]);
        }
        else
        {
            // Установим код ответа - 400 неверный запрос
            http_response_code(400);

            // Возвращаем ответ
            echo json_encode(["message" => "Pass wrong!", "pass" => $data['pass']]);
        }
    }

}
catch (Exception $e) {
    Logger::Exception($e);
}