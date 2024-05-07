<?php

namespace Templates;

use Exception;

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
        $this->addTitle($input, $lang);
        $this->addName($input, $lang);
        $this->addEmail($input, $lang);
        $this->addSubject($input, $lang);
        $this->addBody($input, $lang);
    }

    /**
     * Добавляет e-mail отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     * @throws Exception
     */
    protected function addEmail($input, $lang)
    {
        if (isset($input['email'])) {
            $this->template .= $lang['email'] . $input['email'] . PHP_EOL;
        } else {
            $this->reportBug($input, $lang, 'email');
        }
    }

    /**
     * Добавляет тему к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     */
    protected function addSubject($input, $lang)
    {
        if (isset($input['subject'])) {
            $this->template .= PHP_EOL . implode(PHP_EOL, [$lang['subject'], "```", $input['subject'], "```"]);
        }
    }
}
