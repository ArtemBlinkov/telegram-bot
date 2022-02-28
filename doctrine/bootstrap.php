<?php

//composer
require_once(__DIR__ . '/../vendor/autoload.php');

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

try {

    // Create a simple "default" Doctrine ORM configuration for Annotations
    $paths = __DIR__ . '/models';
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    $useSimpleAnnotationReader = false;

    $config = Setup::createAnnotationMetadataConfiguration([$paths], $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

    // obtaining the entity manager
    $entityManager = EntityManager::create(DB, $config);

}
catch (Exception $e)
{
    Logger::Exception($e);
}
