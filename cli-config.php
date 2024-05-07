<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

try {
    $entityManager = Entity::getEntityManager();

    ConsoleRunner::run(
        new SingleManagerProvider($entityManager)
    );
} catch (Exception $e) {
    Logger::exception($e);
}