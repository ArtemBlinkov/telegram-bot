<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;

require_once(__DIR__ . '/../vendor/autoload.php');

class Entity
{
    /**
     * @return EntityManager
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
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

        return EntityManager::create($conn, $config);
    }
}