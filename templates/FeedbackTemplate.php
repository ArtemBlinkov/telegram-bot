<?php

namespace Templates;

use TelegramBot\Api\Exception;

class FeedbackTemplate extends Template
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
        $this->add_subject($input, $lang);
        $this->add_body($input, $lang);
    }

    /**
     * Добавляет e-mail отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */
    protected function add_email($input, $lang)
    {
        if (isset($input['email'])) {
            // установим электронный адрес написавшего
            $this->template .=
                $lang['email'] . $input['email'] . PHP_EOL;
        } else {
            // сообщим об ошибке
            $this->report_a_bug($input, $lang, 'email');
        }
    }

    /**
     * Добавляет тему к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     */
    protected function add_subject($input, $lang)
    {
        // установим тему сообщения, если она есть в запросе
        if (isset($input['subject'])) {
            $this->template .=
                PHP_EOL . $lang['subject'] . PHP_EOL . "```{$input['subject']}```";
        }
    }
}
