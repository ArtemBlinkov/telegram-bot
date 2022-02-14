<?php

//composer
require_once(__DIR__ . '/../vendor/autoload.php');
//logger
require_once(__DIR__ . '/../classes/Logger.php');

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

try {

    $paths = [$_SERVER['DOCUMENT_ROOT'] . "/models"];
    $isDevMode = true;

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $entityManager = EntityManager::create(DB, $config);

}
catch (Exception $e)
{
    Logger::Exception($e);
}
