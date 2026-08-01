<?php

declare(strict_types=1);

/**
 * Protège une valeur avant son affichage dans une page HTML.
 */
function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Transforme une valeur provenant d’un champ datetime-local en date PHP.
 */
function parseDateTimeLocal(string $value): ?DateTimeImmutable
{
    $date = DateTimeImmutable::createFromFormat(
        '!Y-m-d\TH:i',
        $value
    );

    if ($date === false) {
        return null;
    }

    if ($date->format('Y-m-d\TH:i') !== $value) {
        return null;
    }

    return $date;
}

/**
 * Vérifie les données principales d’un trajet.
 *
 * @return list<string> Liste des messages d’erreur.
 */
function validateTrip(
    string $departureAgencyIdInput,
    string $arrivalAgencyIdInput,
    string $totalSeatsInput,
    string $departureDateInput,
    string $arrivalDateInput
): array {
    $errors = [];

    if ($departureAgencyIdInput === '') {
        $errors[] = 'L’agence de départ est obligatoire.';
    } elseif (
        !ctype_digit($departureAgencyIdInput)
        || (int) $departureAgencyIdInput < 1
    ) {
        $errors[] = 'L’agence de départ sélectionnée est invalide.';
    }

    if ($arrivalAgencyIdInput === '') {
        $errors[] = 'L’agence d’arrivée est obligatoire.';
    } elseif (
        !ctype_digit($arrivalAgencyIdInput)
        || (int) $arrivalAgencyIdInput < 1
    ) {
        $errors[] = 'L’agence d’arrivée sélectionnée est invalide.';
    }

    if (
        ctype_digit($departureAgencyIdInput)
        && ctype_digit($arrivalAgencyIdInput)
        && $departureAgencyIdInput === $arrivalAgencyIdInput
    ) {
        $errors[] = 'Les agences de départ et d’arrivée doivent être différentes.';
    }

    if ($totalSeatsInput === '') {
        $errors[] = 'Le nombre total de places est obligatoire.';
    } elseif (!ctype_digit($totalSeatsInput)) {
        $errors[] = 'Le nombre total de places doit être un nombre entier.';
    } elseif ((int) $totalSeatsInput < 1) {
        $errors[] = 'Le nombre total de places doit être supérieur à zéro.';
    }

    $departureDate = null;
    $arrivalDate = null;

    if ($departureDateInput === '') {
        $errors[] = 'La date de départ est obligatoire.';
    } else {
        $departureDate = parseDateTimeLocal($departureDateInput);

        if ($departureDate === null) {
            $errors[] = 'La date de départ est invalide.';
        }
    }

    if ($arrivalDateInput === '') {
        $errors[] = 'La date d’arrivée est obligatoire.';
    } else {
        $arrivalDate = parseDateTimeLocal($arrivalDateInput);

        if ($arrivalDate === null) {
            $errors[] = 'La date d’arrivée est invalide.';
        }
    }

    if (
        $departureDate !== null
        && $arrivalDate !== null
        && $arrivalDate <= $departureDate
    ) {
        $errors[] = 'L’arrivée doit avoir lieu après le départ.';
    }

    return $errors;
}

/**
 * Formate une date MySQL pour son affichage dans une page.
 */
function formatDateTime(string $value): string
{
    $date = new DateTimeImmutable($value);

    return $date->format('d/m/Y à H:i');
}

/**
 * Vérifie qu’une agence existe dans la base de données.
 */
function agencyExists(PDO $databaseConnection, int $agencyId): bool
{
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM agencies
         WHERE id_agency = :id_agency'
    );

    $statement->execute([
        'id_agency' => $agencyId,
    ]);

    return (int) $statement->fetchColumn() > 0;
}