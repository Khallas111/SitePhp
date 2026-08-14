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