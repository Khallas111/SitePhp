<?php

declare(strict_types=1);
use App\Repository\AgencyRepository;
use App\Validation\TripValidator;

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/vendor/autoload.php';

$databaseConnection = getDatabaseConnection();

$agencyRepository =
    new AgencyRepository($databaseConnection);

$tripValidator = new TripValidator();
$route = trim($_GET['route'] ?? '', '/');

match ($route) {
    '', 'home' => showHomePage($databaseConnection),

    'login' => showLoginPage($databaseConnection),

    'logout' => logoutUser(),

    'trips/create' => showCreateTripPage(
        $databaseConnection,
        $agencyRepository,
        $tripValidator
    ),

    'trips/show' => showTripDetailsPage(
        $databaseConnection
    ),

    'trips/edit' => showEditTripPage(
        $databaseConnection,
        $agencyRepository,
        $tripValidator
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

    'admin/users/edit' => showAdminEditUserPage(
        $databaseConnection
    ),

    'admin/users/delete' => deleteAdminUserAction(
        $databaseConnection
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

    'admin/agencies/delete' => deleteAdminAgencyAction(
        $databaseConnection,
        $agencyRepository
    ),

    default => showNotFoundPage(),
};