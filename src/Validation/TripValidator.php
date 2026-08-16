<?php

declare(strict_types=1);

namespace App\Validation;

final class TripValidator
{
    /**
     * @return list<string>
     */
    public function validate(
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
            $errors[] =
                'Les agences de départ et d’arrivée doivent être différentes.';
        }

        if ($totalSeatsInput === '') {
            $errors[] = 'Le nombre total de places est obligatoire.';
        } elseif (!ctype_digit($totalSeatsInput)) {
            $errors[] =
                'Le nombre total de places doit être un nombre entier.';
        } elseif ((int) $totalSeatsInput < 1) {
            $errors[] =
                'Le nombre total de places doit être supérieur à zéro.';
        }

        $departureDate = null;
        $arrivalDate = null;

        if ($departureDateInput === '') {
            $errors[] = 'La date de départ est obligatoire.';
        } else {
            $departureDate =
                \parseDateTimeLocal($departureDateInput);

            if ($departureDate === null) {
                $errors[] = 'La date de départ est invalide.';
            }
        }

        if ($arrivalDateInput === '') {
            $errors[] = 'La date d’arrivée est obligatoire.';
        } else {
            $arrivalDate =
                \parseDateTimeLocal($arrivalDateInput);

            if ($arrivalDate === null) {
                $errors[] = 'La date d’arrivée est invalide.';
            }
        }

        if (
            $departureDate !== null
            && $arrivalDate !== null
            && $arrivalDate <= $departureDate
        ) {
            $errors[] =
                'L’arrivée doit avoir lieu après le départ.';
        }

        return $errors;
    }
}