<?php


namespace Templates;

use TelegramBot\Api\Exception;

class CommentTemplate extends Template
{

    /**
     * Коструктор класса, исполнение методов
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */

    public function __construct($input, $lang)
    {
        $this->add_title($input, $lang);
        $this->add_name($input, $lang);
        $this->add_body($input, $lang);
    }

    /**
     * Добавляет имя отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */

    protected function add_name($input, $lang)
    {
        if (isset($input['name']))
        {
            // установим имя написавшего
            $this->template .= $lang['name'] . $input['name'] . PHP_EOL;
        }
        else
        {
            // сообщим об ошибке
            $this->report_a_bug($input, $lang, 'name');
        }
    }

}