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
 * Retourne l’utilisateur connecté ou null.
 *
 * @return array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }|null
 */
function getCurrentUser(): ?array
{
    $user = $_SESSION['user'] ?? null;

    if (!is_array($user)) {
        return null;
    }

    return $user;
}

/**
 * Retourne l’utilisateur connecté.
 *
 * Redirige vers la page de connexion lorsqu’aucun utilisateur
 * n’est authentifié.
 *
 * @return array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }
 */
function requireLogin(): array
{
    $currentUser = getCurrentUser();

    if ($currentUser === null) {
        header('Location: index.php?route=login');
        exit;
    }

    return $currentUser;
}

/**
 * Retourne l’administrateur connecté.
 *
 * Redirige un visiteur vers la connexion et affiche
 * une erreur 403 pour un utilisateur non administrateur.
 *
 * @return array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }
 */
function requireAdmin(): array
{
    $currentUser = requireLogin();

    if ($currentUser['role'] !== 'ADMIN') {
        showForbiddenPage();
        exit;
    }

    return $currentUser;
}

/**
 * Retourne le jeton CSRF de la session courante.
 */
function getCsrfToken(): string
{
    $csrfToken = $_SESSION['csrf_token'] ?? null;

    if (!is_string($csrfToken)) {
        $csrfToken = bin2hex(random_bytes(32));

        $_SESSION['csrf_token'] = $csrfToken;
    }

    return $csrfToken;
}

/**
 * Vérifie qu’un jeton CSRF correspond à celui de la session.
 */
function isCsrfTokenValid(mixed $csrfToken): bool
{
    $sessionToken = $_SESSION['csrf_token'] ?? null;

    if (!is_string($csrfToken) || !is_string($sessionToken)) {
        return false;
    }

    return hash_equals($sessionToken, $csrfToken);
}

/**
 * Indique si un utilisateur peut gérer un trajet.
 *
 * L’auteur et un administrateur sont autorisés.
 *
 * @param array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 */
function canManageTrip(
    array $currentUser,
    int $authorId
): bool {
    return $currentUser['id_user'] === $authorId
        || $currentUser['role'] === 'ADMIN';
}

/**
 * Formate une date MySQL pour un champ datetime-local.
 */
function formatDateTimeLocalInput(string $value): string
{
    $date = new DateTimeImmutable($value);

    return $date->format('Y-m-d\TH:i');
}