<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use TelegramBot\Api\Client,
    TelegramBot\Api\Exception,
    TelegramBot\Api\Types\Update;

try {

    /**
     * Исполнение чат-бота
     */

    // инициализация телеграмм бота
    $bot = new Client(APIKEY);

    // точка входа, обработка прав и ошибок
    $bot->on(function (Update $update) use ($bot)
    {
        // получаем данные от телеграмма
        $message = $update->getMessage();

        // подключение языкового файла
        $lang = Lang::IncludeFile('main.php', $message->getFrom()->getLanguageCode());

        // проверяем, что команды поступают от меня
        if ($message->getFrom()->getId() == ME)
        {
            // проверяем запрос на наличие команды (синтаксис)
            preg_match(Client::REGEXP, $message->getText(), $matches);

            // если в запросе нет команды - отправить ошибку
            if (empty($matches))
            {
                $bot->sendMessage($message->getChat()->getId(), $lang['empty_command']);
                return;
            }
            else
            {
                // получаем список команд из языкового файла
                $command = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());

                // ищем команду в массиве, если её нет, отправляем ошибку
                if (!$command[$matches[1]])
                {
                    $bot->sendMessage($message->getChat()->getId(), $lang['undefined_command']);
                    return;
                }
            }
        }
        else
        {
            // отправляем сообщение об ошибке доступа
            $bot->sendMessage($message->getChat()->getId(), $lang['no_access']);
        }

    }, function () {
        return true;
    });

    // подключаем базовые команды
    require_once('base.php');

    // подключаем контроллер отработки ответа на отзыв
    require_once('answer.php');

    // запуск бота
    $bot->run();

} catch (Exception $e) {
    Logger::Exception($e);
}