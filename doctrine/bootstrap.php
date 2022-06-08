<?php
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

require_once(__DIR__ . '/../vendor/autoload.php');

try {

    // Create a simple "default" Doctrine ORM configuration for Annotations
    $paths = [
        __DIR__ . '/src'
    ];
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;

    $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);

    $conn = DriverManager::getConnection(DB);

    // obtaining the entity manager
    $entityManager = EntityManager::create($conn, $config);

}
catch (Exception $e)
{
    Logger::Exception($e);
}
