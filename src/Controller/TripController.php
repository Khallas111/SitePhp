<?php

declare(strict_types=1);

/**
 * Prépare, traite et affiche le formulaire de création d’un trajet.
 */
function showCreateTripPage(PDO $databaseConnection): void
{

    $applicationName = 'Klaxon';
    $pageTitle = 'Proposer un trajet';

    $currentUser = requireLogin();
    $csrfToken = getCsrfToken();

    $departureAgencyIdInput = '';
    $arrivalAgencyIdInput = '';
    $totalSeatsInput = '';
    $departureDateInput = '';
    $arrivalDateInput = '';

    $errors = [];
    $successMessage = '';

    $departureAgencyId = null;
    $arrivalAgencyId = null;
    $departureDate = null;
    $arrivalDate = null;
    $totalSeats = null;

    $agencies = findAllAgencies($databaseConnection);

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['created'] ?? '') === '1'
    ) {
        $successMessage = 'Le trajet a été créé avec succès.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $departureAgencyIdInput = trim(
            $_POST['departureAgencyId'] ?? ''
        );

        $arrivalAgencyIdInput = trim(
            $_POST['arrivalAgencyId'] ?? ''
        );

        $totalSeatsInput = trim(
            $_POST['totalSeats'] ?? ''
        );

        $departureDateInput = trim(
            $_POST['departureDate'] ?? ''
        );

        $arrivalDateInput = trim(
            $_POST['arrivalDate'] ?? ''
        );

        if (!isCsrfTokenValid($_POST['csrf_token'] ?? null)) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if ($errors === []) {
            $errors = validateTrip(
                $departureAgencyIdInput,
                $arrivalAgencyIdInput,
                $totalSeatsInput,
                $departureDateInput,
                $arrivalDateInput
            );
        }

        if ($errors === []) {
            $departureAgencyId = (int) $departureAgencyIdInput;
            $arrivalAgencyId = (int) $arrivalAgencyIdInput;

            if (
                !agencyExists(
                    $databaseConnection,
                    $departureAgencyId
                )
            ) {
                $errors[] =
                    'L’agence de départ sélectionnée n’existe pas.';
            }

            if (
                !agencyExists(
                    $databaseConnection,
                    $arrivalAgencyId
                )
            ) {
                $errors[] =
                    'L’agence d’arrivée sélectionnée n’existe pas.';
            }
        }

        if ($errors === []) {
            $departureDate = parseDateTimeLocal(
                $departureDateInput
            );

            $arrivalDate = parseDateTimeLocal(
                $arrivalDateInput
            );

            if (
                $departureDate === null
                || $arrivalDate === null
            ) {
                $errors[] = 'Les dates du trajet sont invalides.';
            }
        }

        if ($errors === []) {
            $totalSeats = (int) $totalSeatsInput;
        }

        if (
            $errors === []
            && $departureAgencyId !== null
            && $arrivalAgencyId !== null
            && $departureDate !== null
            && $arrivalDate !== null
            && $totalSeats !== null
        ) {
            $authorId = $currentUser['id_user'];

            createTrip(
                $databaseConnection,
                $departureDate,
                $arrivalDate,
                $totalSeats,
                $authorId,
                $departureAgencyId,
                $arrivalAgencyId
            );

            header(
                'Location: index.php?route=trips/create&created=1'
            );
            exit;
        }
    }

    require __DIR__ . '/../View/trip/create.php';
}

/**
 * Affiche les informations détaillées d’un trajet.
 */
function showTripDetailsPage(
    PDO $databaseConnection
): void {
    $currentUser = requireLogin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Détails du trajet';
    $csrfToken = getCsrfToken();

    $tripIdInput = $_GET['id'] ?? null;

    if (
        !is_string($tripIdInput)
        || !ctype_digit($tripIdInput)
        || (int) $tripIdInput < 1
    ) {
        showNotFoundPage();
        return;
    }

    $tripId = (int) $tripIdInput;

    $trip = findTripDetailsById(
        $databaseConnection,
        $tripId
    );

    if ($trip === null) {
        showNotFoundPage();
        return;
    }

    $isAuthor =
        $trip['author_id'] === $currentUser['id_user'];

    require __DIR__ . '/../View/trip/show.php';
}