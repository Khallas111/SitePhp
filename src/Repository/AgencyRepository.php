<?php

declare(strict_types=1);

/**
 * Récupère toutes les agences par ordre alphabétique.
 *
 * @return list<array<string, mixed>>
 */
function findAllAgencies(PDO $databaseConnection): array
{
    $statement = $databaseConnection->query(
        'SELECT
            id_agency,
            city
         FROM agencies
         ORDER BY city ASC'
    );

    if ($statement === false) {
        return [];
    }

    return $statement->fetchAll();
}

/**
 * Vérifie qu’une agence existe dans la base de données.
 */
function agencyExists(
    PDO $databaseConnection,
    int $agencyId
): bool {
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

/**
 * Indique si une agence existe déjà pour cette ville.
 */
function agencyCityExists(
    PDO $databaseConnection,
    string $city
): bool {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM agencies
         WHERE city = :city'
    );

    $statement->execute([
        'city' => $city,
    ]);

    return (int) $statement->fetchColumn() > 0;
}

/**
 * Crée une nouvelle agence.
 *
 * @return int Identifiant de la nouvelle agence.
 */
function createAgency(
    PDO $databaseConnection,
    string $city
): int {
    $statement = $databaseConnection->prepare(
        'INSERT INTO agencies (city)
         VALUES (:city)'
    );

    $statement->execute([
        'city' => $city,
    ]);

    return (int) $databaseConnection->lastInsertId();
}

/**
 * Recherche une agence à partir de son identifiant.
 *
 * @return array{
 *     id_agency: int,
 *     city: string
 * }|null
 */
function findAgencyById(
    PDO $databaseConnection,
    int $agencyId
): ?array {
    $statement = $databaseConnection->prepare(
        'SELECT
            id_agency,
            city
         FROM agencies
         WHERE id_agency = :id_agency
         LIMIT 1'
    );

    $statement->execute([
        'id_agency' => $agencyId,
    ]);

    $agency = $statement->fetch();

    if ($agency === false) {
        return null;
    }

    return [
        'id_agency' => (int) $agency['id_agency'],
        'city' => $agency['city'],
    ];
}

/**
 * Indique si une ville appartient déjà à une autre agence.
 */
function agencyCityExistsForAnotherAgency(
    PDO $databaseConnection,
    string $city,
    int $excludedAgencyId
): bool {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM agencies
         WHERE city = :city
           AND id_agency != :excluded_agency_id'
    );

    $statement->execute([
        'city' => $city,
        'excluded_agency_id' => $excludedAgencyId,
    ]);

    return (int) $statement->fetchColumn() > 0;
}

/**
 * Met à jour une agence existante.
 */
function updateAgency(
    PDO $databaseConnection,
    int $agencyId,
    string $city
): void {
    $statement = $databaseConnection->prepare(
        'UPDATE agencies
         SET city = :city
         WHERE id_agency = :id_agency'
    );

    $statement->execute([
        'city' => $city,
        'id_agency' => $agencyId,
    ]);
}