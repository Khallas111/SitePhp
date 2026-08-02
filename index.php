<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__
    . '/src/Repository/TripRepository.php';
require_once __DIR__
    . '/src/Controller/HomeController.php';

$databaseConnection = getDatabaseConnection();

showHomePage($databaseConnection);