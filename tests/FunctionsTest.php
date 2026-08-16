<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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


    public function testParseDateTimeLocalRejectsImpossibleDate(): void
    {
        $date = parseDateTimeLocal(
            '2026-02-31T10:30'
        );

        self::assertNull($date);
    }

}