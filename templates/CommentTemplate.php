<?php

namespace Templates;

use Exception;

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
        $this->addTitle($input, $lang);
        $this->addName($input, $lang);
        $this->addEmail($input, $lang);
        $this->addBody($input, $lang);
    }

    /**
     * Добавляет e-mail отправителя к шаблону
     * @param $input - данные POST
     * @param $lang - данные языкового файла
     */
    protected function addEmail($input, $lang)
    {
        if (isset($input['email'])) {
            $this->template .= $lang['email'] . $input['email'] . PHP_EOL;
        }
    }
}
