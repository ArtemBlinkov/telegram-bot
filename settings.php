<?php

/**
 * Настройки php
 */

error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);

/**
 * Константы
 */

const
    // Ключ интеграции с телеграмм ботом
    APIKEY = '5084252788:AAG0lSgaPWfv4K53vVpJufwafWbb4jQLs_Y',
    // id чата со мной
    ME = '496112875',
    // Язык по умполчанию
    LANG = 'ru',
    //База данных
    DB = [
        'driver'   => 'pdo_mysql',
        'user'     => 'u1416043_tbot',
        'password' => 'razor2517',
        'dbname'   => 'u1416043_tbot_base',
        'host' => 'localhost'
    ];

/**
 * Ключ безопасности между приложениями
 */

define('PASS', sha1( date('d.m.Y H') . 'artem' ));