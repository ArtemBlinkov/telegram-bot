<?php
//Заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//constant
require_once(__DIR__ . '/settings.php');

//composer
require_once(__DIR__ . '/vendor/autoload.php');

//Классы
require_once(__DIR__ . '/classes/Logger.php');
require_once(__DIR__ . '/classes/Lang.php');

//Шаблоны
require_once(__DIR__ . '/templates/Template.php');
require_once(__DIR__ . '/templates/FeedbackTemplate.php');
require_once(__DIR__ . '/templates/CommentTemplate.php');