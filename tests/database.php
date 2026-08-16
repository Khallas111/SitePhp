<?php

declare(strict_types=1);

function getTestDatabaseConnection(): PDO
{
    $host = '127.0.0.1';
    $port = 3306;
    $database = 'klaxon_test';
    $username = 'root';
    $password = '';

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $host,
        $port,
        $database
    );

    return new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE =>
                PDO::ERRMODE_EXCEPTION,

            PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,

            PDO::ATTR_EMULATE_PREPARES =>
                false,
        ]
    );
}

function resetTestDatabase(
    PDO $databaseConnection
): void {
    $schemaPath =
        __DIR__
        . '/../database/test-schema.sql';

    $schema = file_get_contents(
        $schemaPath
    );

    if ($schema === false) {
        throw new RuntimeException(
            'Impossible de lire le schéma de test.'
        );
    }

    $databaseConnection->exec(
        $schema
    );
}
