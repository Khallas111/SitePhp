<?php

declare(strict_types=1);

/**
 * Recherche un utilisateur à partir de son adresse email.
 *
 * @return array{
 *     id_user: int|string,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     password_hash: string,
 *     phone: string,
 *     role: string
 * }|null
 */
function findUserByEmail(
    PDO $databaseConnection,
    string $email
): ?array {
    $statement = $databaseConnection->prepare(
        'SELECT
            id_user,
            first_name,
            last_name,
            email,
            password_hash,
            phone,
            role
         FROM users
         WHERE email = :email
         LIMIT 1'
    );

    $statement->execute([
        'email' => $email,
    ]);

    $user = $statement->fetch();

    if ($user === false) {
        return null;
    }

    return $user;
}