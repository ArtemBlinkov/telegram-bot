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
        $this->add_email($input, $lang);
        $this->add_body($input, $lang);
    }

    /**
     * Добавляет e-mail отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     */
    protected function add_email($input, $lang)
    {
        if (isset($input['email'])) {
            // установим электронный адрес написавшего
            $this->template .=
                $lang['email'] . $input['email'] . PHP_EOL;
        }
    }
}
