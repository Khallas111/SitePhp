<?php

declare(strict_types=1);

use App\Repository\TripRepository;

/**
 * Prépare et affiche la page d’accueil.
 */
function showHomePage(
    TripRepository $tripRepository
): void {
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

    $trips =
        $tripRepository->findAvailableFuture();

    require __DIR__ . '/../View/home.php';
}