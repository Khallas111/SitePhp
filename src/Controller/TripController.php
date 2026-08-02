<?php

declare(strict_types=1);

/**
 * Prépare, traite et affiche le formulaire de création d’un trajet.
 */
function showCreateTripPage(PDO $databaseConnection): void
{
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

        $errors = validateTrip(
            $departureAgencyIdInput,
            $arrivalAgencyIdInput,
            $totalSeatsInput,
            $departureDateInput,
            $arrivalDateInput
        );

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
            // Temporaire : remplacé plus tard par l’utilisateur connecté.
            $temporaryAuthorId = 2;

            createTrip(
                $databaseConnection,
                $departureDate,
                $arrivalDate,
                $totalSeats,
                $temporaryAuthorId,
                $departureAgencyId,
                $arrivalAgencyId
            );

            header('Location: create-trip.php?created=1');
            exit;
        }
    }

    require __DIR__ . '/../View/trip/create.php';
}