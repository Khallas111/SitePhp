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

    $trips = findAvailableFutureTrips($databaseConnection);

    require __DIR__ . '/../View/home.php';
}