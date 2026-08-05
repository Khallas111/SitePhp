<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

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

require_once __DIR__
    . '/src/Controller/AdminController.php';

$databaseConnection = getDatabaseConnection();

$route = trim($_GET['route'] ?? '', '/');

match ($route) {
    '', 'home' => showHomePage($databaseConnection),

    'login' => showLoginPage($databaseConnection),

    'logout' => logoutUser(),

    'trips/create' => showCreateTripPage(
        $databaseConnection
    ),

    'trips/show' => showTripDetailsPage(
        $databaseConnection
    ),

    'trips/edit' => showEditTripPage(
        $databaseConnection
    ),

    'trips/delete' => deleteTripAction(
        $databaseConnection
    ),

    'admin/users' => showAdminUsersPage(
        $databaseConnection
    ),

    'admin/users/create' => showAdminCreateUserPage(
        $databaseConnection
    ),

    default => showNotFoundPage(),
};