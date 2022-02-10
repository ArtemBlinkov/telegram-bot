<?php

namespace TelegramBot;

use TelegramBot\Api\Exception;

class Template
{

    public $template = '';

    /**
     * Возвращает готовый шаблон сообщения
     * @return string
     */

    public function get() : string
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
        // установим ip адрес или домен сервера, с котрого пришло сообщение
        $this->template .= $input['domain'] ?? $_SERVER['REMOTE_ADDR'];

        // установим заголовок сообщения
        $this->template .= ' - ' . $lang['title'] . PHP_EOL . PHP_EOL;
    }

    /**
     * Добавляет сообщение к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */

    protected function add_body($input, $lang)
    {
        if (isset($input['body']))
        {
            // установим тело сообщения
            $this->template .= PHP_EOL . $lang['body'] . PHP_EOL . '_"' . $input['body'] . '"_' . PHP_EOL;
        }
        else
        {
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