<?php

if (!defined('LANG')) {
    define("LANG", 'ru');
}

class Lang
{
    /**
     * Подключение языкового файла
     * @param $file string - имя файла с расширением
     * @param $lang string - текущий язык
     * @return array
     * @throws Exception
     */
    public static function includeFile(string $file, string $lang = LANG): array
    {
        $dir = self::getLangPath();

        $file_path = $dir . $lang . '/' . $file;

        if (is_file($file_path)) {
            return include $file_path;
        } elseif ($lang !== LANG) {
            self::includeFile($file);
        } else {
            throw new Exception("Error: File '$file_path' not found!");
        }

        return [];
    }

    /**
     * Получить начальный путь
     * @return string
     */
    public static function getLangPath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/lang/';
    }

    /**
     * Подключение всех языковых файлов выбранного языка и помещение их содержимого в массив
     * @param $lang string - текущий язык
     * @return array
     * @throws Exception
     */
    public static function includeAllFiles(string $lang = LANG): array
    {
        $dir = self::getLangPath();
        $path = $dir . $lang . '/';
        $lang_list = [];

        if ($handle = opendir($path)) {
            while ($entry = readdir($handle)) {
                if (strpos($entry, ".php") !== false) {
                    $value = require_once($path . $entry);
                    $name = str_replace('.php', '', $entry);
                    $lang_list[$name] = $value;
                }
            }
            closedir($handle);
        } elseif ($lang !== LANG) {
            self::IncludeAllFiles();
        } else {
            throw new Exception("Error: Directory '$path' not found!");
        }

        return $lang_list;
    }
}
