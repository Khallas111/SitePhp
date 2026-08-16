<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/vendor/autoload.php';

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

    'admin/users/edit' => showAdminEditUserPage(
        $databaseConnection
    ),

    'admin/users/delete' => deleteAdminUserAction(
        $databaseConnection
    ),

    'admin/agencies' => showAdminAgenciesPage(
        $databaseConnection
    ),

    'admin/agencies/create' => showAdminCreateAgencyPage(
        $databaseConnection
    ),

    'admin/agencies/edit' => showAdminEditAgencyPage(
        $databaseConnection
    ),

    'admin/agencies/delete' => deleteAdminAgencyAction(
        $databaseConnection
    ),

    default => showNotFoundPage(),
};