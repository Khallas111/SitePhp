<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__
    . '/src/Repository/AgencyRepository.php';

require_once __DIR__
    . '/src/Repository/TripRepository.php';

require_once __DIR__
    . '/src/Controller/TripController.php';

$databaseConnection = getDatabaseConnection();

showCreateTripPage($databaseConnection);