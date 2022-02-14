<?php

require_once __DIR__ . "/doctrine/bootstrap.php";

use Doctrine\ORM\Tools\Console\ConsoleRunner;

global $entityManager;

return ConsoleRunner::createHelperSet($entityManager);