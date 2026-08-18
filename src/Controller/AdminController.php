<?php

declare(strict_types=1);
use App\Repository\AgencyRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;

/**
 * Affiche la liste des utilisateurs aux administrateurs.
 */
function showAdminUsersPage(
    UserRepository $userRepository
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Utilisateurs';

    $csrfToken = getCsrfToken();

    $users = $userRepository->findAll();

    require __DIR__
        . '/../View/admin/users/index.php';
}

/**
 * Affiche tous les trajets aux administrateurs.
 */
function showAdminTripsPage(
    TripRepository $tripRepository
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Gestion des trajets';
    $csrfToken = getCsrfToken();
    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['deleted'] ?? '') === '1'
    ) {
        $successMessage =
            'Le trajet a été supprimé avec succès.';
    }

    $trips = $tripRepository->findAll();

    require __DIR__
        . '/../View/admin/trips/index.php';
}

/**
 * Affiche la liste des agences.
 */
function showAdminAgenciesPage(
    AgencyRepository $agencyRepository
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Gestion des agences';
    $csrfToken = getCsrfToken();

    $successMessage = '';
    $errorMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['deleted'] ?? '') === '1'
    ) {
        $successMessage =
            'L’agence a été supprimée avec succès.';
    }

    if (($_GET['error'] ?? '') === 'has_trips') {
        $errorMessage =
            'Cette agence est utilisée par un ou plusieurs '
            . 'trajets et ne peut pas être supprimée.';
    }

    $agencies = $agencyRepository->findAll();
    require __DIR__
        . '/../View/admin/agencies/index.php';
}

/**
 * Affiche et traite le formulaire de création d’une agence.
 */
function showAdminCreateAgencyPage(
    AgencyRepository $agencyRepository
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Ajouter une agence';
    $csrfToken = getCsrfToken();

    $cityInput = '';

    $errors = [];
    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['created'] ?? '') === '1'
    ) {
        $successMessage =
            'L’agence a été créée avec succès.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cityInput = trim(
            $_POST['city'] ?? ''
        );

        if (
            !isCsrfTokenValid(
                $_POST['csrf_token'] ?? null
            )
        ) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if (
            $errors === []
            && $cityInput === ''
        ) {
            $errors[] =
                'La ville de l’agence est obligatoire.';
        }

        if (
            $errors === []
            && $agencyRepository->cityExists($cityInput)
        ) {
            $errors[] =
                'Une agence existe déjà pour cette ville.';
        }

        if ($errors === []) {
            $agencyRepository->create($cityInput);

            header(
                'Location: index.php'
                . '?route=admin/agencies/create'
                . '&created=1'
            );
            exit;
        }
    }

    require __DIR__
        . '/../View/admin/agencies/create.php';
}

/**
 * Affiche et traite le formulaire de modification d’une agence.
 */
function showAdminEditAgencyPage(
    AgencyRepository $agencyRepository
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Modifier une agence';
    $csrfToken = getCsrfToken();

    $agencyIdInput = $_GET['id'] ?? null;

    if (
        !is_string($agencyIdInput)
        || !ctype_digit($agencyIdInput)
        || (int) $agencyIdInput < 1
    ) {
        showNotFoundPage();
        return;
    }

    $agencyId = (int) $agencyIdInput;

    $agency = $agencyRepository->findById($agencyId);

    if ($agency === null) {
        showNotFoundPage();
        return;
    }

    $cityInput = $agency['city'];

    $errors = [];
    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['updated'] ?? '') === '1'
    ) {
        $successMessage =
            'L’agence a été modifiée avec succès.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cityInput = trim(
            $_POST['city'] ?? ''
        );

        if (
            !isCsrfTokenValid(
                $_POST['csrf_token'] ?? null
            )
        ) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if (
            $errors === []
            && $cityInput === ''
        ) {
            $errors[] =
                'La ville de l’agence est obligatoire.';
        }

        if (
            $errors === []
            && $agencyRepository->cityExistsForAnotherAgency(
                $cityInput,
                $agencyId
            )
        ) {
            $errors[] =
                'Une autre agence existe déjà pour cette ville.';
        }

        if ($errors === []) {
            $agencyRepository->update(
                $agencyId,
                $cityInput
            );

            header(
                'Location: index.php'
                . '?route=admin/agencies/edit'
                . '&id=' . $agencyId
                . '&updated=1'
            );
            exit;
        }
    }

    require __DIR__
        . '/../View/admin/agencies/edit.php';
}

/**
 * Supprime une agence lorsqu’elle n’est utilisée
 * par aucun trajet.
 */
function deleteAdminAgencyAction(
    AgencyRepository $agencyRepository,
    TripRepository $tripRepository
): void {
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);

        echo 'Méthode non autorisée.';
        return;
    }

    if (
        !isCsrfTokenValid(
            $_POST['csrf_token'] ?? null
        )
    ) {
        showForbiddenPage();
        return;
    }

    $agencyIdInput = $_POST['agency_id'] ?? null;

    if (
        !is_string($agencyIdInput)
        || !ctype_digit($agencyIdInput)
        || (int) $agencyIdInput < 1
    ) {
        showNotFoundPage();
        return;
    }

    $agencyId = (int) $agencyIdInput;

    $agency = $agencyRepository->findById($agencyId);

    if ($agency === null) {
        showNotFoundPage();
        return;
    }

    $tripCount = $tripRepository->countUsingAgency(
        $agencyId
    );

    if ($tripCount > 0) {
        header(
            'Location: index.php'
            . '?route=admin/agencies'
            . '&error=has_trips'
        );
        exit;
    }

    $agencyRepository->deleteById($agencyId);

    header(
        'Location: index.php'
        . '?route=admin/agencies'
        . '&deleted=1'
    );
    exit;
}
