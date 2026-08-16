<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/functions.php';

final class FunctionsTest extends TestCase
{
    public function testParseDateTimeLocalReturnsDateForValidValue(): void
    {
        $date = parseDateTimeLocal('2026-08-20T14:30');

        self::assertInstanceOf(
            DateTimeImmutable::class,
            $date
        );

        self::assertSame(
            '2026-08-20 14:30',
            $date->format('Y-m-d H:i')
        );
    }

    public function testParseDateTimeLocalReturnsNullForInvalidValue(): void
    {
        $date = parseDateTimeLocal('bonjour');

        self::assertNull($date);
    }

    public function testValidateTripAcceptsValidTrip(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertSame([], $errors);
    }

    public function testValidateTripRejectsSameAgencies(): void
    {
        $errors = validateTrip(
            '1',
            '1',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsZeroSeats(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '0',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsNegativeSeats(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '-5',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsNonNumericSeats(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            'quatre',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsArrivalBeforeDeparture(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '4',
            '2026-08-20T10:30',
            '2026-08-20T08:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsSameDepartureAndArrivalTime(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '4',
            '2026-08-20T10:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsInvalidDate(): void
    {
        $errors = validateTrip(
            '1',
            '2',
            '4',
            'bonjour',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testParseDateTimeLocalRejectsImpossibleDate(): void
    {
        $date = parseDateTimeLocal(
            '2026-02-31T10:30'
        );

        self::assertNull($date);
    }

    public function testValidateTripRejectsInvalidAgencyId(): void
    {
        $errors = validateTrip(
            'bonjour',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }

    public function testValidateTripRejectsZeroAgencyId(): void
    {
        $errors = validateTrip(
            '0',
            '2',
            '4',
            '2026-08-20T08:30',
            '2026-08-20T10:30'
        );

        self::assertNotEmpty($errors);
    }
}