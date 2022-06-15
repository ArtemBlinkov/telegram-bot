<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

require_once(__DIR__ . '/../vendor/autoload.php');

class Entity
{
    /**
     * @return \Doctrine\ORM\EntityManager
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public static function getEntityManager(): EntityManager
    {
        // каталог с аннтотациями (представления таблиц)
        $paths = [
            __DIR__ . '/../doctrine/src'
        ];

        // поставить true для возможности управление через командную строку
        $isDevMode = true;

        $proxyDir = null;
        $cache = null;

        $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);

        $conn = DriverManager::getConnection(DB);

        // obtaining the entity manager
        return EntityManager::create($conn, $config);
    }
}