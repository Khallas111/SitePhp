<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Repository\TripRepository;
use DateTimeImmutable;
use PDO;
use PHPUnit\Framework\TestCase;

final class TripRepositoryTest extends TestCase
{
    private PDO $databaseConnection;
    private TripRepository $tripRepository;

    protected function setUp(): void
    {
        $this->databaseConnection =
            \getTestDatabaseConnection();

        $this->databaseConnection
            ->beginTransaction();

        $this->tripRepository =
            new TripRepository(
                $this->databaseConnection
            );
    }

    protected function tearDown(): void
    {
        if (
            $this->databaseConnection
                ->inTransaction()
        ) {
            $this->databaseConnection
                ->rollBack();
        }
    }

    private function createAgency(
        string $city
    ): int {
        $statement =
            $this->databaseConnection->prepare(
                'INSERT INTO agencies (city)
             VALUES (:city)'
            );

        $statement->execute([
            'city' => $city,
        ]);

        return (int) 
            $this->databaseConnection
                ->lastInsertId();
    }

    private function createUser(
        string $email = 'test@example.test'
    ): int {
        $statement =
            $this->databaseConnection->prepare(
                'INSERT INTO users (
                first_name,
                last_name,
                email,
                password_hash,
                phone,
                role
            ) VALUES (
                :first_name,
                :last_name,
                :email,
                :password_hash,
                :phone,
                :role
            )'
            );

        $statement->execute([
            'first_name' => 'Jean',
            'last_name' => 'Test',
            'email' => $email,
            'password_hash' =>
                password_hash(
                    'Test123!',
                    PASSWORD_DEFAULT
                ),
            'phone' => '0600000000',
            'role' => 'USER',
        ]);

        return (int) 
            $this->databaseConnection
                ->lastInsertId();
    }

    private function createTripFixture(
        int $authorId,
        int $departureAgencyId,
        int $arrivalAgencyId,
        DateTimeImmutable $departure,
        DateTimeImmutable $arrival,
        int $totalSeats = 4,
        int $availableSeats = 4
    ): int {
        $statement =
            $this->databaseConnection->prepare(
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
            'departure_at' =>
                $departure->format(
                    'Y-m-d H:i:s'
                ),

            'arrival_at' =>
                $arrival->format(
                    'Y-m-d H:i:s'
                ),

            'total_seats' => $totalSeats,
            'available_seats' =>
                $availableSeats,

            'author_id' => $authorId,

            'departure_agency_id' =>
                $departureAgencyId,

            'arrival_agency_id' =>
                $arrivalAgencyId,
        ]);

        return (int) 
            $this->databaseConnection
                ->lastInsertId();
    }

    public function testCreateTrip(): void
    {
        $departureAgencyId =
            $this->createAgency('Perpignan');

        $arrivalAgencyId =
            $this->createAgency('Montpellier');

        $authorId =
            $this->createUser();

        $departure =
            new DateTimeImmutable('+2 days');

        $arrival =
            $departure->modify('+2 hours');

        $this->tripRepository->create(
            $departure,
            $arrival,
            4,
            $authorId,
            $departureAgencyId,
            $arrivalAgencyId
        );

        $tripId = (int) 
            $this->databaseConnection
                ->lastInsertId();

        $trip =
            $this->tripRepository
                ->findDetailsById($tripId);

        self::assertNotNull($trip);

        self::assertSame(
            4,
            $trip['total_seats']
        );

        self::assertSame(
            4,
            $trip['available_seats']
        );

        self::assertSame(
            $authorId,
            $trip['author_id']
        );
    }

    public function testFindDetailsById(): void
    {
        $departureAgencyId =
            $this->createAgency('Perpignan');

        $arrivalAgencyId =
            $this->createAgency('Toulouse');

        $authorId =
            $this->createUser();

        $departure =
            new DateTimeImmutable('+3 days');

        $arrival =
            $departure->modify('+3 hours');

        $tripId =
            $this->createTripFixture(
                $authorId,
                $departureAgencyId,
                $arrivalAgencyId,
                $departure,
                $arrival,
                4,
                2
            );

        $trip =
            $this->tripRepository
                ->findDetailsById($tripId);

        self::assertNotNull($trip);

        self::assertSame(
            'Perpignan',
            $trip['departure_city']
        );

        self::assertSame(
            'Toulouse',
            $trip['arrival_city']
        );

        self::assertSame(
            'Jean',
            $trip['author_first_name']
        );

        self::assertSame(
            'Test',
            $trip['author_last_name']
        );

        self::assertSame(
            2,
            $trip['available_seats']
        );
    }

    public function testFindDetailsByIdReturnsNullForUnknownTrip(): void
    {
        $trip =
            $this->tripRepository
                ->findDetailsById(999999);

        self::assertNull($trip);
    }

    public function testFindAvailableFutureTrips(): void
    {
        $departureAgencyId =
            $this->createAgency('Perpignan');

        $arrivalAgencyId =
            $this->createAgency('Montpellier');

        $authorId =
            $this->createUser();

        $futureDeparture =
            new DateTimeImmutable('+2 days');

        $futureArrival =
            $futureDeparture->modify('+2 hours');

        $expectedTripId =
            $this->createTripFixture(
                $authorId,
                $departureAgencyId,
                $arrivalAgencyId,
                $futureDeparture,
                $futureArrival,
                4,
                2
            );

        $this->createTripFixture(
            $authorId,
            $departureAgencyId,
            $arrivalAgencyId,
            $futureDeparture->modify('+1 day'),
            $futureArrival->modify('+1 day'),
            4,
            0
        );

        $pastDeparture =
            new DateTimeImmutable('-2 days');

        $this->createTripFixture(
            $authorId,
            $departureAgencyId,
            $arrivalAgencyId,
            $pastDeparture,
            $pastDeparture->modify('+2 hours'),
            4,
            3
        );

        $trips =
            $this->tripRepository
                ->findAvailableFuture();

        self::assertCount(1, $trips);

        self::assertSame(
            $expectedTripId,
            $trips[0]['id_trip']
        );
    }

    public function testFindAllTripsIncludesPastAndFullTrips(): void
    {
        $departureAgencyId =
            $this->createAgency('Perpignan');

        $arrivalAgencyId =
            $this->createAgency('Montpellier');

        $authorId = $this->createUser();

        $futureDeparture =
            new DateTimeImmutable('+3 days');

        $futureTripId = $this->createTripFixture(
            $authorId,
            $departureAgencyId,
            $arrivalAgencyId,
            $futureDeparture,
            $futureDeparture->modify('+2 hours'),
            4,
            0
        );

        $pastDeparture =
            new DateTimeImmutable('-3 days');

        $pastTripId = $this->createTripFixture(
            $authorId,
            $departureAgencyId,
            $arrivalAgencyId,
            $pastDeparture,
            $pastDeparture->modify('+2 hours')
        );

        $trips = $this->tripRepository->findAll();

        self::assertCount(2, $trips);
        self::assertSame($pastTripId, $trips[0]['id_trip']);
        self::assertSame($futureTripId, $trips[1]['id_trip']);
    }

    public function testUpdateTrip(): void
    {
        $perpignanId =
            $this->createAgency('Perpignan');

        $montpellierId =
            $this->createAgency('Montpellier');

        $toulouseId =
            $this->createAgency('Toulouse');

        $authorId =
            $this->createUser();

        $departure =
            new DateTimeImmutable('+2 days');

        $tripId =
            $this->createTripFixture(
                $authorId,
                $perpignanId,
                $montpellierId,
                $departure,
                $departure->modify('+2 hours')
            );

        $newDeparture =
            new DateTimeImmutable('+5 days');

        $newArrival =
            $newDeparture->modify('+4 hours');

        $this->tripRepository->update(
            $tripId,
            $newDeparture,
            $newArrival,
            6,
            5,
            $perpignanId,
            $toulouseId
        );

        $trip =
            $this->tripRepository
                ->findDetailsById($tripId);

        self::assertNotNull($trip);

        self::assertSame(
            'Toulouse',
            $trip['arrival_city']
        );

        self::assertSame(
            6,
            $trip['total_seats']
        );

        self::assertSame(
            5,
            $trip['available_seats']
        );
    }

    public function testDeleteTrip(): void
    {
        $departureAgencyId =
            $this->createAgency('Perpignan');

        $arrivalAgencyId =
            $this->createAgency('Narbonne');

        $authorId =
            $this->createUser();

        $departure =
            new DateTimeImmutable('+2 days');

        $tripId =
            $this->createTripFixture(
                $authorId,
                $departureAgencyId,
                $arrivalAgencyId,
                $departure,
                $departure->modify('+1 hour')
            );

        self::assertNotNull(
            $this->tripRepository
                ->findDetailsById($tripId)
        );

        $this->tripRepository
            ->deleteById($tripId);

        self::assertNull(
            $this->tripRepository
                ->findDetailsById($tripId)
        );
    }

    public function testCountByAuthor(): void
    {
        $perpignanId =
            $this->createAgency('Perpignan');

        $montpellierId =
            $this->createAgency('Montpellier');

        $authorId =
            $this->createUser();

        $otherAuthorId =
            $this->createUser(
                'other@example.test'
            );

        $departure =
            new DateTimeImmutable('+2 days');

        $this->createTripFixture(
            $authorId,
            $perpignanId,
            $montpellierId,
            $departure,
            $departure->modify('+2 hours')
        );

        $this->createTripFixture(
            $authorId,
            $perpignanId,
            $montpellierId,
            $departure->modify('+1 day'),
            $departure->modify('+1 day +2 hours')
        );

        $this->createTripFixture(
            $otherAuthorId,
            $perpignanId,
            $montpellierId,
            $departure->modify('+2 days'),
            $departure->modify('+2 days +2 hours')
        );

        self::assertSame(
            2,
            $this->tripRepository
                ->countByAuthor($authorId)
        );

        self::assertSame(
            1,
            $this->tripRepository
                ->countByAuthor($otherAuthorId)
        );
    }

    public function testCountUsingAgency(): void
    {
        $perpignanId =
            $this->createAgency('Perpignan');

        $montpellierId =
            $this->createAgency('Montpellier');

        $toulouseId =
            $this->createAgency('Toulouse');

        $authorId =
            $this->createUser();

        $departure =
            new DateTimeImmutable('+2 days');

        // Perpignan en départ
        $this->createTripFixture(
            $authorId,
            $perpignanId,
            $montpellierId,
            $departure,
            $departure->modify('+2 hours')
        );

        // Perpignan en arrivée
        $this->createTripFixture(
            $authorId,
            $toulouseId,
            $perpignanId,
            $departure->modify('+1 day'),
            $departure->modify('+1 day +2 hours')
        );

        // Aucun lien avec Perpignan
        $this->createTripFixture(
            $authorId,
            $toulouseId,
            $montpellierId,
            $departure->modify('+2 days'),
            $departure->modify('+2 days +2 hours')
        );

        self::assertSame(
            2,
            $this->tripRepository
                ->countUsingAgency($perpignanId)
        );
    }
}
