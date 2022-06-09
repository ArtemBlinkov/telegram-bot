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

    // проверяем данные post на наличие
    if ($bot->getRawBody())
    {
        /**
         * Обработка прав доступа и ошибок, уникальных ответов
         */
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

                // создаём объект бд
                $db = new CommentRecord();

                // если в запросе нет команды - отправить ошибку
                if (empty($matches))
                {
                    // проверяем, что в данный момент нет комментариев, ожидающих ответа и выводим ошибку
                    if (!$db->checkComment())
                    {
                        $bot->sendMessage($message->getChat()->getId(), $lang['empty_command']);
                        return false;
                    }
                }
                else
                {
                    // проверям комментарии в бд, если есть комментарии со статусом wait, игнорируем другие команды и ждём ответа
                    if ($db->checkComment())
                    {
                        $bot->sendMessage($message->getChat()->getId(), $lang['wait_answer']);
                        return false;
                    }
                    else
                    {
                        // получаем список команд из языкового файла
                        $command = Lang::IncludeFile('command.php', $message->getFrom()->getLanguageCode());

                        // ищем команду в массиве, если её нет, отправляем ошибку
                        if (!$command[$matches[1]]) {
                            $bot->sendMessage($message->getChat()->getId(), $lang['undefined_command']);
                            return false;
                        }
                    }
                }
            }
            else
            {
                // отправляем сообщение об ошибке доступа
                $bot->sendMessage($message->getChat()->getId(), $lang['no_access']);
                return false;
            }

            return true;

        }, function () {
            return true;
        });

        /**
         * Подключаем базовые команды
         */
        require_once('base.php');

        /**
         * Подключаем контроллер отработки ответа на отзыв
         */
        require_once('comment.php');

        /**
         * Запуск бота
         */
        $bot->run();
    }

} catch (Exception $e) {
    Logger::Exception($e);
}