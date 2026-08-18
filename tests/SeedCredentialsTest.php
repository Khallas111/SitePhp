<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class SeedCredentialsTest extends TestCase
{
    public function testDocumentedPasswordsMatchSeedHashes(): void
    {
        $seed = file_get_contents(
            __DIR__ . '/../database/seed.sql'
        );

        self::assertIsString($seed);

        $matchCount = preg_match_all(
            "/'(\\$2y\\$[^']+)'/",
            $seed,
            $matches
        );

        self::assertSame(2, $matchCount);
        self::assertTrue(
            password_verify('Admin123!', $matches[1][0])
        );
        self::assertTrue(
            password_verify('User123!', $matches[1][1])
        );
    }
}
