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