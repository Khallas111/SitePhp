<?php

declare(strict_types=1);
use App\Validation\TripValidator;

/**
 * Prépare, traite et affiche le formulaire de création d’un trajet.
 */
function showCreateTripPage(
    PDO $databaseConnection,
    TripValidator $tripValidator
): void {

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
            $errors = $tripValidator->validate(
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
            && $departureDate !== null
            && $arrivalDate !== null
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

    $canManageTrip = canManageTrip(
        $currentUser,
        $trip['author_id']
    );

    require __DIR__ . '/../View/trip/show.php';
}

/**
 * Affiche et traite le formulaire de modification d’un trajet.
 */
function showEditTripPage(
    PDO $databaseConnection,
    TripValidator $tripValidator
): void {
    $currentUser = requireLogin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Modifier un trajet';
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

    if (!canManageTrip($currentUser, $trip['author_id'])) {
        showForbiddenPage();
        return;
    }

    $agencies = findAllAgencies($databaseConnection);

    $departureAgencyIdInput =
        (string) $trip['departure_agency_id'];

    $arrivalAgencyIdInput =
        (string) $trip['arrival_agency_id'];

    $departureDateInput =
        formatDateTimeLocalInput($trip['departure_at']);

    $arrivalDateInput =
        formatDateTimeLocalInput($trip['arrival_at']);

    $totalSeatsInput = (string) $trip['total_seats'];

    $errors = [];
    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['updated'] ?? '') === '1'
    ) {
        $successMessage =
            'Le trajet a été modifié avec succès.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $departureAgencyIdInput = trim(
            $_POST['departureAgencyId'] ?? ''
        );

        $arrivalAgencyIdInput = trim(
            $_POST['arrivalAgencyId'] ?? ''
        );

        $departureDateInput = trim(
            $_POST['departureDate'] ?? ''
        );

        $arrivalDateInput = trim(
            $_POST['arrivalDate'] ?? ''
        );

        $totalSeatsInput = trim(
            $_POST['totalSeats'] ?? ''
        );

        if (
            !isCsrfTokenValid(
                $_POST['csrf_token'] ?? null
            )
        ) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if ($errors === []) {
            $tripValidator = new TripValidator();

            $errors = $tripValidator->validate(
                $departureAgencyIdInput,
                $arrivalAgencyIdInput,
                $totalSeatsInput,
                $departureDateInput,
                $arrivalDateInput
            );
        }

        if ($errors === []) {
            $departureAgencyId =
                (int) $departureAgencyIdInput;

            $arrivalAgencyId =
                (int) $arrivalAgencyIdInput;

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
                $errors[] =
                    'Les dates du trajet sont invalides.';
            }
        }

        if ($errors === []) {
            $totalSeats = (int) $totalSeatsInput;

            $occupiedSeats =
                $trip['total_seats']
                - $trip['available_seats'];

            if ($totalSeats < $occupiedSeats) {
                $errors[] =
                    'Le nombre total de places ne peut pas '
                    . 'être inférieur au nombre de places '
                    . 'déjà occupées.';
            } else {
                $availableSeats =
                    $totalSeats - $occupiedSeats;
            }
        }

        if ($errors === []) {
            updateTrip(
                $databaseConnection,
                $tripId,
                $departureDate,
                $arrivalDate,
                $totalSeats,
                $availableSeats,
                $departureAgencyId,
                $arrivalAgencyId
            );

            header(
                'Location: index.php'
                . '?route=trips/edit'
                . '&id=' . $tripId
                . '&updated=1'
            );
            exit;
        }
    }

    require __DIR__ . '/../View/trip/edit.php';
}

/**
 * Supprime un trajet lorsque l’utilisateur possède les droits nécessaires.
 */
function deleteTripAction(
    PDO $databaseConnection
): void {
    $currentUser = requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);

        echo 'Méthode non autorisée.';
        return;
    }

    if (!isCsrfTokenValid($_POST['csrf_token'] ?? null)) {
        showForbiddenPage();
        return;
    }

    $tripIdInput = $_POST['trip_id'] ?? null;

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

    if (!canManageTrip($currentUser, $trip['author_id'])) {
        showForbiddenPage();
        return;
    }

    deleteTripById(
        $databaseConnection,
        $tripId
    );

    header('Location: index.php?deleted=1');
    exit;
}