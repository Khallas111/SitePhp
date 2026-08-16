<?php

declare(strict_types=1);
use App\Repository\AgencyRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Validation\TripValidator;


error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/vendor/autoload.php';
$databaseConnection =
    getDatabaseConnection();

$agencyRepository =
    new AgencyRepository(
        $databaseConnection
    );

$tripRepository =
    new TripRepository(
        $databaseConnection
    );

$userRepository =
    new UserRepository(
        $databaseConnection
    );

$tripValidator =
    new TripValidator();
$route = trim($_GET['route'] ?? '', '/');

match ($route) {
    '', 'home' => showHomePage($tripRepository),

    'login' =>
    showLoginPage(
        $userRepository
    ),

    'logout' => logoutUser(),

    'trips/create' =>
    showCreateTripPage(
        $agencyRepository,
        $tripRepository,
        $tripValidator
    ),

    'trips/show' =>
    showTripDetailsPage(
        $tripRepository
    ),

    'trips/edit' =>
    showEditTripPage(
        $agencyRepository,
        $tripRepository,
        $tripValidator
    ),

    'trips/delete' =>
    deleteTripAction(
        $tripRepository
    ),

    'admin/users' =>
    showAdminUsersPage(
        $userRepository
    ),

    'admin/users/create' =>
    showAdminCreateUserPage(
        $userRepository
    ),
    'admin/users/edit' => showAdminEditUserPage(
        $userRepository
    ),

    'admin/users/delete' =>
    deleteAdminUserAction(
        $userRepository,
        $tripRepository
    ),

    'admin/agencies' =>
    showAdminAgenciesPage(
        $databaseConnection,
        $agencyRepository
    ),

    'admin/agencies/create' => showAdminCreateAgencyPage(
        $databaseConnection,
        $agencyRepository
    ),

    'admin/agencies/edit' => showAdminEditAgencyPage(
        $databaseConnection,
        $agencyRepository
    ),

    'admin/agencies/delete' =>
    deleteAdminAgencyAction(
        $agencyRepository,
        $tripRepository
    ),

    default => showNotFoundPage(),
};