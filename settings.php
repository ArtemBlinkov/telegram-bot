<?php
//Настройки php
error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);

//Константы
const
    APIKEY = '5084252788:AAG0lSgaPWfv4K53vVpJufwafWbb4jQLs_Y',
    ME = '496112875',
    LANG = 'ru';

define('PASS', sha1( date('d.m.Y H') . 'artem' ));