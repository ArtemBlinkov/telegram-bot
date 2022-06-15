<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once(__DIR__ . '/../../../vendor/autoload.php');

use TelegramBot\Api\BotApi;

try {

    //Получаем данные запроса
    $post = file_get_contents('php://input');

    if ($post)
    {
        // создаём экземпляр бота
        $bot = new BotApi(APIKEY);

        // помещаем данные в массив
        $data = $bot::jsonValidate($post, true);

        // проверяем, что запрос пришёл с проверенного сайта
        if ($data['pass'] === PASS)
        {
            // удаляем запись из бд
            $db = new CommentRecord();

            if ($db->deleteComment($data['id']))
            {
                // Установим код ответа - 200 всё хорошо
                http_response_code(200);

                // Возвращаем ответ
                echo json_encode(["message" => "ok"]);
            }
            else
            {
                // установим код ответа - 404 отзыв не найден
                http_response_code(404);

                // возвращаем ответ
                echo json_encode(["message" => "Review don't found"]);
            }
        }
        else
        {
            // Установим код ответа - 400 неверный запрос
            http_response_code(400);

            // Возвращаем ответ
            echo json_encode(["message" => "Authorization failed!"]);
        }
    }

}
catch (Exception $e) {
    Logger::Exception($e);
}