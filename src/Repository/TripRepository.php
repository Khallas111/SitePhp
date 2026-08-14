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

/**
 * Recherche les informations détaillées d’un trajet.
 *
 * @return array{
 *     id_trip: int,
 *     departure_city: string,
 *     departure_at: string,
 *     arrival_city: string,
 *     arrival_at: string,
 *     total_seats: int,
 *     available_seats: int,
 *     author_id: int,
 *     author_first_name: string,
 *     author_last_name: string,
 *     author_email: string,
 *     author_phone: string
 * }|null
 */
function findTripDetailsById(
    PDO $databaseConnection,
    int $tripId
): ?array {
    $statement = $databaseConnection->prepare(
        'SELECT
        trips.id_trip,
        trips.departure_agency_id,
        departure_agency.city AS departure_city,
        trips.departure_at,
        trips.arrival_agency_id,
        arrival_agency.city AS arrival_city,
        trips.arrival_at,
        trips.total_seats,
        trips.available_seats,
        users.id_user AS author_id,
        users.first_name AS author_first_name,
        users.last_name AS author_last_name,
        users.email AS author_email,
        users.phone AS author_phone
    FROM trips
    INNER JOIN agencies AS departure_agency
        ON trips.departure_agency_id =
           departure_agency.id_agency
    INNER JOIN agencies AS arrival_agency
        ON trips.arrival_agency_id =
           arrival_agency.id_agency
    INNER JOIN users
        ON trips.author_id = users.id_user
    WHERE trips.id_trip = :id_trip
    LIMIT 1'
    );

    $statement->execute([
        'id_trip' => $tripId,
    ]);

    $trip = $statement->fetch();

    if ($trip === false) {
        return null;
    }

    return [
        'id_trip' => (int) $trip['id_trip'],
        'departure_agency_id' =>
            (int) $trip['departure_agency_id'],
        'departure_city' => $trip['departure_city'],
        'departure_at' => $trip['departure_at'],
        'arrival_agency_id' =>
            (int) $trip['arrival_agency_id'],
        'arrival_city' => $trip['arrival_city'],
        'arrival_at' => $trip['arrival_at'],
        'total_seats' => (int) $trip['total_seats'],
        'available_seats' => (int) $trip['available_seats'],
        'author_id' => (int) $trip['author_id'],
        'author_first_name' => $trip['author_first_name'],
        'author_last_name' => $trip['author_last_name'],
        'author_email' => $trip['author_email'],
        'author_phone' => $trip['author_phone'],
    ];
}

/**
 * Met à jour un trajet existant.
 */
function updateTrip(
    PDO $databaseConnection,
    int $tripId,
    DateTimeImmutable $departureDate,
    DateTimeImmutable $arrivalDate,
    int $totalSeats,
    int $availableSeats,
    int $departureAgencyId,
    int $arrivalAgencyId
): void {
    $statement = $databaseConnection->prepare(
        'UPDATE trips
         SET
            departure_at = :departure_at,
            arrival_at = :arrival_at,
            total_seats = :total_seats,
            available_seats = :available_seats,
            departure_agency_id = :departure_agency_id,
            arrival_agency_id = :arrival_agency_id
         WHERE id_trip = :id_trip'
    );

    $statement->execute([
        'id_trip' => $tripId,
        'departure_at' =>
            $departureDate->format('Y-m-d H:i:s'),
        'arrival_at' =>
            $arrivalDate->format('Y-m-d H:i:s'),
        'total_seats' => $totalSeats,
        'available_seats' => $availableSeats,
        'departure_agency_id' => $departureAgencyId,
        'arrival_agency_id' => $arrivalAgencyId,
    ]);
}

/**
 * Supprime un trajet à partir de son identifiant.
 */
function deleteTripById(
    PDO $databaseConnection,
    int $tripId
): void {
    $statement = $databaseConnection->prepare(
        'DELETE FROM trips
         WHERE id_trip = :id_trip'
    );

    $statement->execute([
        'id_trip' => $tripId,
    ]);
}

/**
 * Retourne le nombre de trajets créés par un utilisateur.
 */
function countTripsByAuthor(
    PDO $databaseConnection,
    int $authorId
): int {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM trips
         WHERE author_id = :author_id'
    );

    $statement->execute([
        'author_id' => $authorId,
    ]);

    return (int) $statement->fetchColumn();
}

/**
 * Retourne le nombre de trajets utilisant une agence
 * comme départ ou comme arrivée.
 */
function countTripsUsingAgency(
    PDO $databaseConnection,
    int $agencyId
): int {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM trips
         WHERE departure_agency_id = :departure_agency_id
            OR arrival_agency_id = :arrival_agency_id'
    );

    $statement->execute([
        'departure_agency_id' => $agencyId,
        'arrival_agency_id' => $agencyId,
    ]);

    return (int) $statement->fetchColumn();
}