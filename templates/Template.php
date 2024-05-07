<?php

namespace Templates;

use Exception;

class Template
{
    public const SMILE = '🔹';

    /**
     * @var string
     */
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
    protected function addTitle($input, $lang)
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
    protected function addName($input, $lang)
    {
        if (isset($input['name'])) {
            $this->template .= $lang['name'] . $input['name'] . PHP_EOL;
        } else {
            $this->reportBug($input, $lang, 'name');
        }
    }

    /**
     * Добавляет сообщение к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */
    protected function addBody($input, $lang)
    {
        if (isset($input['body'])) {
            $this->template .= PHP_EOL . implode(PHP_EOL, [$lang['body'], "```", $input['body'], "```"]) . PHP_EOL;
        } else {
            $this->reportBug($input, $lang, 'body');
        }
    }

    /**
     * Сообщение об ошибке
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @param $field_name - название поля
     * @throws Exception
     */
    protected function reportBug($input, $lang, $field_name)
    {
        http_response_code(404);
        echo json_encode(["message" => $field_name . $lang['error'], "data" => $input]);
        throw new Exception($field_name . $lang['error']);
    }
}
