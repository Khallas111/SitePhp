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
 * Vérifie les données principales d’un trajet.
 *
 * @return list<string> Liste des messages d’erreur.
 */
function validateTrip(
    string $departureAgency,
    string $arrivalAgency,
    string $totalSeatsInput
): array {
    $errors = [];

    if ($departureAgency === '') {
        $errors[] = 'L’agence de départ est obligatoire.';
    }

    if ($arrivalAgency === '') {
        $errors[] = 'L’agence d’arrivée est obligatoire.';
    }

    if (
        $departureAgency !== ''
        && $arrivalAgency !== ''
        && $departureAgency === $arrivalAgency
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

    return $errors;
}