<?php

declare(strict_types=1);

/**
 * Récupère les trajets futurs ayant encore des places disponibles.
 *
 * @return list<array<string, mixed>>
 */
function findAvailableFutureTrips(PDO $databaseConnection): array
{
    $statement = $databaseConnection->query(
        'SELECT
            trips.id_trip,
            departure_agency.city AS departure_city,
            trips.departure_at,
            arrival_agency.city AS arrival_city,
            trips.arrival_at,
            trips.available_seats,
            trips.total_seats
         FROM trips
         INNER JOIN agencies AS departure_agency
            ON trips.departure_agency_id =
               departure_agency.id_agency
         INNER JOIN agencies AS arrival_agency
            ON trips.arrival_agency_id =
               arrival_agency.id_agency
         WHERE trips.available_seats > 0
            AND trips.departure_at > NOW()
         ORDER BY trips.departure_at ASC'
    );

    if ($statement === false) {
        return [];
    }

    return $statement->fetchAll();
}

/**
 * Enregistre un nouveau trajet dans la base de données.
 */
function createTrip(
    PDO $databaseConnection,
    DateTimeImmutable $departureDate,
    DateTimeImmutable $arrivalDate,
    int $totalSeats,
    int $authorId,
    int $departureAgencyId,
    int $arrivalAgencyId
): void {
    $statement = $databaseConnection->prepare(
        'INSERT INTO trips (
            departure_at,
            arrival_at,
            total_seats,
            available_seats,
            author_id,
            departure_agency_id,
            arrival_agency_id
        ) VALUES (
            :departure_at,
            :arrival_at,
            :total_seats,
            :available_seats,
            :author_id,
            :departure_agency_id,
            :arrival_agency_id
        )'
    );

    $statement->execute([
        'departure_at' => $departureDate->format(
            'Y-m-d H:i:s'
        ),
        'arrival_at' => $arrivalDate->format(
            'Y-m-d H:i:s'
        ),
        'total_seats' => $totalSeats,
        'available_seats' => $totalSeats,
        'author_id' => $authorId,
        'departure_agency_id' => $departureAgencyId,
        'arrival_agency_id' => $arrivalAgencyId,
    ]);
}