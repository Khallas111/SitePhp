<?php

declare(strict_types=1);

namespace Tests\Validation;

use App\Validation\TripValidator;
use PHPUnit\Framework\TestCase;

final class TripValidatorTest extends TestCase
{
    private TripValidator $tripValidator;

    protected function setUp(): void
    {
        $this->tripValidator = new TripValidator();
    }

    public function testAcceptsValidTrip(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertSame([], $errors);
    }

    public function testRejectsSameAgencies(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '1',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsZeroSeats(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '0',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsNegativeSeats(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '-5',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsNonNumericSeats(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            'quatre',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsArrivalBeforeDeparture(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '4',
            '2026-08-20T10:30',
            '2026-08-20T08:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsSameDepartureAndArrivalTime(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '4',
            '2026-08-20T10:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsInvalidDate(): void
    {
        $errors = $this->tripValidator->validate(
            '1',
            '2',
            '4',
            'bonjour',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }
    public function testRejectsInvalidAgencyId(): void
    {
        $errors = $this->tripValidator->validate(
            'bonjour',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testRejectsZeroAgencyId(): void
    {
        $errors = $this->tripValidator->validate(
            '0',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }
}