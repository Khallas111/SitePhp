<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__
    . '/src/Repository/AgencyRepository.php';

require_once __DIR__
    . '/src/Repository/TripRepository.php';

require_once __DIR__
    . '/src/Repository/UserRepository.php';

require_once __DIR__
    . '/src/Controller/HomeController.php';

require_once __DIR__
    . '/src/Controller/TripController.php';

require_once __DIR__
    . '/src/Controller/AuthController.php';

require_once __DIR__
    . '/src/Controller/ErrorController.php';

$databaseConnection = getDatabaseConnection();

$route = trim($_GET['route'] ?? '', '/');

match ($route) {
    '', 'home' => showHomePage($databaseConnection),

    'login' => showLoginPage($databaseConnection),

    'logout' => logoutUser(),

    'trips/create' => showCreateTripPage(
        $databaseConnection
    ),

    default => showNotFoundPage(),
};