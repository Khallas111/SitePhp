<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class AgencyRepository
{
    public function __construct(
        private readonly PDO $databaseConnection
    ) {
    }

    /**
     * Récupère toutes les agences par ordre alphabétique.
     *
     * @return list<array{
     *     id_agency: int,
     *     city: string
     *  }>
     */
    public function findAll(): array
    {
        $statement = $this->databaseConnection->query(
            'SELECT
            id_agency,
            city
         FROM agencies
         ORDER BY city ASC'
        );

        $agencies = [];

        foreach ($statement->fetchAll() as $agency) {
            $agencies[] = [
                'id_agency' => (int) $agency['id_agency'],
                'city' => $agency['city'],
            ];
        }

        return $agencies;
    }

    /**
     * Vérifie qu’une agence existe dans la base de données.
     */
    public function exists(int $agencyId): bool
    {
        $statement = $this->databaseConnection->prepare(
            'SELECT 1
         FROM agencies
         WHERE id_agency = :id_agency'
        );

        $statement->execute([
            'id_agency' => $agencyId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Indique si une agence existe déjà pour cette ville.
     */
    public function cityExists(string $city): bool
    {
        $statement = $this->databaseConnection->prepare(
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
    public function create(string $city): int
    {
        $statement = $this->databaseConnection->prepare(
            'INSERT INTO agencies (city)
         VALUES (:city)'
        );

        $statement->execute([
            'city' => $city,
        ]);

        return (int) $this->databaseConnection->lastInsertId();
    }

    /**
     * Recherche une agence à partir de son identifiant.
     *
     * @return array{
     *     id_agency: int,
     *     city: string
     * }|null
     */
    public function findById(int $agencyId): ?array
    {
        $statement = $this->databaseConnection->prepare(
            'SELECT
            id_agency,
            city
         FROM agencies
         WHERE id_agency = :id_agency'
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
    public function cityExistsForAnotherAgency(
        string $city,
        int $agencyId
    ): bool {
        $statement = $this->databaseConnection->prepare(
            'SELECT 1
         FROM agencies
         WHERE city = :city
           AND id_agency != :id_agency'
        );

        $statement->execute([
            'city' => $city,
            'id_agency' => $agencyId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Met à jour une agence existante.
     */
    public function update(
        int $agencyId,
        string $city
    ): void {
        $statement = $this->databaseConnection->prepare(
            'UPDATE agencies
         SET city = :city
         WHERE id_agency = :id_agency'
        );

        $statement->execute([
            'city' => $city,
            'id_agency' => $agencyId,
        ]);
    }

    /**
     * Supprime une agence à partir de son identifiant.
     */
    public function deleteById(int $agencyId): void
    {
        $statement = $this->databaseConnection->prepare(
            'DELETE FROM agencies
         WHERE id_agency = :id_agency'
        );

        $statement->execute([
            'id_agency' => $agencyId,
        ]);
    }
}