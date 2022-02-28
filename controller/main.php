<?php
//Подключаем header
require_once(__DIR__ . '/../header.php');

use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;

try {

    /**
     * Исполнение чат-бота
     */

    //Инициализация телеграмм бота
    $bot = new Client(APIKEY);

    //Получаем входные данные
    $post = $bot->getRawBody();

    //Если есть входные данные
    if (!empty($post))
    {
        //Получаем массив данных от телеграмма
        $input = json_decode($post, true);

        //Отрабатываем callback - нажатие по кнопке
        if (isset($input['callback_query']))
        {
            //TODO: Убрать после отладки
            Logger::Debug($input['callback_query']);
        }

        //Отрабатываем ввод сообщения или команды
        if (isset($input['message']))
        {
            //TODO: Убрать после отладки
            Logger::Debug($input['message']);

            //Подключение языкового файла
            $lang = Lang::IncludeFile('command.php', $input['message']['from']['language_code']);

            //Подключение команд
            require_once(__DIR__ . '/command.php');

            //Запуск бота
            $bot->run();
        }
    }

} catch (Exception $e) {
    Logger::Exception($e);
}