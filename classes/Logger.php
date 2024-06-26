<?php

/**
 * Настройки php
 *
 *  error_reporting(E_ALL);
 *  ini_set('display_errors',0);
 *  ini_set('log_errors',1);
 *
 */

set_error_handler('Logger::error', error_reporting());
set_exception_handler('Logger::exception');

class Logger
{
    /**
     * Функция обработчки php ошибок
     * @param $severity - уровень ошибки
     * @param $message - сообщение об ошибке в виде строки
     * @param $file - имя файла, в котором произошла ошибка, в виде строки
     * @param $line - номер строки, в которой произошла ошибка, в виде целого числа
     */
    public static function error($severity = 0, $message = "", $file = __FILE__, $line = __LINE__)
    {
        $path = self::checkCatalog();
        $error = '-------------Error--------------' . PHP_EOL;
        $error .= 'Time: ' . date('d.m.Y H.i.s') . PHP_EOL;
        $error .= 'Severity: ' . $severity . PHP_EOL;
        $error .= 'Line: ' . $line . PHP_EOL;
        $error .= 'File: ' . $file . PHP_EOL;
        $error .= 'Message: ' . $message . PHP_EOL;
        $error .= '-------------------------------' . PHP_EOL;
        file_put_contents($path . 'error.log', $error, FILE_APPEND);
    }

    /**
     * Функция для проверки существования папки debug в корне приложения
     * @return string
     */
    public static function checkCatalog(): string
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/logs/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * Функция логирования исключений
     * @param $exception - объект исключения
     */
    public static function exception($exception)
    {
        $path = self::checkCatalog();
        $error = '-----------Exception------------' . PHP_EOL;
        $error .= 'Time: ' . date('d.m.Y H.i.s') . PHP_EOL;
        $error .= 'Code: ' . $exception->getCode() . PHP_EOL;
        $error .= 'Message: ' . $exception->getMessage() . PHP_EOL;
        $error .= '--------------Trace-------------' . PHP_EOL;
        $error .= $exception->getTraceAsString() . PHP_EOL;
        $error .= '-------------------------------' . PHP_EOL;
        file_put_contents($path . 'error.log', $error, FILE_APPEND);
    }

    /**
     * Функция для вывода любой информации в лог
     * @param $data - информация для логирования
     */
    public static function debug($data)
    {
        $path = self::checkCatalog();
        $debug = '-----------Debug------------' . PHP_EOL;
        $debug .= 'Time: ' . date('d.m.Y H.i.s') . PHP_EOL;
        $debug .= print_r($data, true) . PHP_EOL;
        $debug .= '----------------------------' . PHP_EOL;
        file_put_contents($path . 'debug.log', $debug, FILE_APPEND);
    }
}
