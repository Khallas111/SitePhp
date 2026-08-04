<?php

declare(strict_types=1);

/**
 * Prépare et affiche la page d’accueil.
 */
function showHomePage(PDO $databaseConnection): void
{
    $applicationName = 'Klaxon';
    $pageTitle = 'Accueil';
    $description = 'Application de covoiturage inter-sites';

    $currentUser = getCurrentUser();
    $csrfToken = getCsrfToken();

    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['deleted'] ?? '') === '1'
    ) {
        $successMessage =
            'Le trajet a été supprimé avec succès.';
    }

    $trips = findAvailableFutureTrips(
        $databaseConnection
    );

    require __DIR__ . '/../View/home.php';
}