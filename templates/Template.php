<?php

namespace Templates;

use TelegramBot\Api\Exception;

class Template
{
    const SMILE = '🔹';

    protected string $template = '';

    /**
     * Возвращает готовый шаблон сообщения
     * @return string
     */
    public function get(): string
    {
        return $this->template;
    }

    /**
     * Добавляет заголовок к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     */
    protected function add_title($input, $lang)
    {
        // определим домен
        $domain = $input['domain'] ?? $_SERVER['REMOTE_ADDR'];

        // добавим эмодзи в начало сообщения
        $this->template .= self::SMILE;

        // установим заголовок сообщения
        $this->template .= " [{$lang['title']}]";

        // установим ip адрес или домен сервера, с котрого пришло сообщение
        $this->template .= "($domain)" . PHP_EOL . PHP_EOL;
    }

    /**
     * Добавляет имя отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */
    protected function add_name($input, $lang)
    {
        if (isset($input['name'])) {
            // установим имя написавшего
            $this->template .=
                $lang['name'] . $input['name'] . PHP_EOL;
        } else {
            // сообщим об ошибке
            $this->report_a_bug($input, $lang, 'name');
        }
    }

    /**
     * Добавляет сообщение к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */
    protected function add_body($input, $lang)
    {
        if (isset($input['body'])) {
            // установим тело сообщения
            $this->template .=
                PHP_EOL . $lang['body'] . PHP_EOL . "```{$input['body']}```" . PHP_EOL;
        } else {
            // сообщим об ошибке
            $this->report_a_bug($input, $lang, 'body');
        }
    }

    /**
     * Сообщение об ошибке
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @param $field_name - название поля
     * @throws Exception
     */
    protected function report_a_bug($input, $lang, $field_name)
    {
        // установим код ответа - 404 сообщение не найдено
        http_response_code(404);

        // отправим ответ
        echo json_encode(["message" => $field_name . $lang['error'], "data" => $input]);

        // бросаем исключение  - обязательный параметр не заполнен
        throw new Exception($field_name . $lang['error']);
    }
}
