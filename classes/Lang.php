<?php

use TelegramBot\Api\Exception;

//Проверка наличия константы стандартного языка
if (!defined(LANG)) {
    define("LANG", 'ru');
}

class Lang
{

    /**
     * Коструктор класса, объявление переменных
     * @return string
     */

    public static function get_lang_path() : string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/lang/';
    }

    /**
     * Подключение языкового файла
     * @param $file string - имя файла с расширением
     * @param $lang string - текущий язык
     * @return array
     * @throws Exception
     */

    public static function IncludeFile(string $file, string $lang = LANG) : array
    {
        // Получаем текущее расположение каталога с языками
        $dir = self::get_lang_path();
        // Создаём путь
        $file_path = $dir . $lang . '/' . $file;

        if (is_file($file_path))
        {
            return require_once($file_path);
        }
        else
        {
            //Если мы не находим файл с вызванным языком, пробуем найти со стандартным языком
            if ($lang !== LANG)
                self::IncludeFile($file);
            else
                throw new Exception("Error: File '$file_path' not found!");
        }

        return [];
    }

    /**
     * Подключение всех языковых файлов выбранного языка и помещение их содержимого в массив
     * @param $lang string - текущий язык
     * @return array
     * @throws Exception
     */

    public function IncludeAllFiles(string $lang = LANG) : array
    {
        // Получаем текущее расположение каталога с языками
        $dir = self::get_lang_path();
        // Создаём путь
        $path = $dir . $lang . '/';
        // Хранилище для языковых фраз
        $lang_list = [];

        //Скнируем папку текущего языка и подключаем все файлы, собираем их содержимое в массив
        if ($handle = opendir($path))
        {
            while ($entry = readdir($handle))
            {
                if (strpos($entry, ".php") !== false)
                {
                    //Получаем содержимое файла
                    $value = require_once($path . $entry);
                    //Получаем имя файла без формата
                    $name = str_replace('.php', '', $entry);
                    //Записываем в общий массив
                    $lang_list[$name] = $value;
                }
            }
            closedir($handle);
        }
        else
        {
            //Если мы не находим файл с вызванным языком, пробуем найти со стандартным языком
            if ($lang !== LANG)
                self::IncludeAllFiles();
            else
                throw new Exception("Error: Directory '$path' not found!");
        }

        //Возвращаем массив
        return $lang_list;
    }

}