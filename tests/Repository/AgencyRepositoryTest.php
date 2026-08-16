<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Repository\AgencyRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class AgencyRepositoryTest extends TestCase
{
    private PDO $databaseConnection;
    private AgencyRepository $agencyRepository;



    protected function setUp(): void
    {
        $this->databaseConnection =
            \getTestDatabaseConnection();

        $this->databaseConnection->exec(
            'DELETE FROM agencies'
        );

        $this->agencyRepository =
            new AgencyRepository(
                $this->databaseConnection
            );
    }

    public function testCreateAgency(): void
    {
        $agencyId =
            $this->agencyRepository->create(
                'Perpignan'
            );

        self::assertGreaterThan(
            0,
            $agencyId
        );

        $agency =
            $this->agencyRepository->findById(
                $agencyId
            );

        self::assertNotNull($agency);

        self::assertSame(
            'Perpignan',
            $agency['city']
        );
    }

    public function testFindByIdReturnsNullForUnknownAgency(): void
    {
        $agency =
            $this->agencyRepository->findById(
                999999
            );

        self::assertNull($agency);
    }

    public function testAgencyExists(): void
    {
        $agencyId =
            $this->agencyRepository->create(
                'Montpellier'
            );

        self::assertTrue(
            $this->agencyRepository->exists(
                $agencyId
            )
        );

        self::assertFalse(
            $this->agencyRepository->exists(
                999999
            )
        );
    }

    public function testCityExists(): void
    {
        $this->agencyRepository->create(
            'Toulouse'
        );

        self::assertTrue(
            $this->agencyRepository->cityExists(
                'Toulouse'
            )
        );

        self::assertFalse(
            $this->agencyRepository->cityExists(
                'Narbonne'
            )
        );
    }

    public function testFindAllAgencies(): void
    {
        $this->agencyRepository->create(
            'Toulouse'
        );

        $this->agencyRepository->create(
            'Montpellier'
        );

        $this->agencyRepository->create(
            'Perpignan'
        );

        $agencies =
            $this->agencyRepository->findAll();

        self::assertCount(
            3,
            $agencies
        );
        self::assertSame(
            'Montpellier',
            $agencies[0]['city']
        );

        self::assertSame(
            'Perpignan',
            $agencies[1]['city']
        );

        self::assertSame(
            'Toulouse',
            $agencies[2]['city']
        );
    }

    public function testUpdateAgency(): void
    {
        $agencyId =
            $this->agencyRepository->create(
                'Perpignan'
            );

        $this->agencyRepository->update(
            $agencyId,
            'Narbonne'
        );

        $agency =
            $this->agencyRepository->findById(
                $agencyId
            );

        self::assertNotNull($agency);

        self::assertSame(
            'Narbonne',
            $agency['city']
        );
    }

    public function testCityExistsForAnotherAgency(): void
    {
        $perpignanId =
            $this->agencyRepository->create(
                'Perpignan'
            );

        $montpellierId =
            $this->agencyRepository->create(
                'Montpellier'
            );

        self::assertFalse(
            $this->agencyRepository
                ->cityExistsForAnotherAgency(
                    'Perpignan',
                    $perpignanId
                )
        );

        self::assertTrue(
            $this->agencyRepository
                ->cityExistsForAnotherAgency(
                    'Montpellier',
                    $perpignanId
                )
        );
    }

    public function testDeleteAgency(): void
    {
        $agencyId =
            $this->agencyRepository->create(
                'Narbonne'
            );

        self::assertTrue(
            $this->agencyRepository->exists(
                $agencyId
            )
        );

        $this->agencyRepository->deleteById(
            $agencyId
        );

        self::assertFalse(
            $this->agencyRepository->exists(
                $agencyId
            )
        );
    }
}