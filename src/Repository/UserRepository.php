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

/**
 * Retourne la liste des utilisateurs.
 *
 * Le hash du mot de passe n’est volontairement pas sélectionné.
 *
 * @return list<array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }>
 */
function findAllUsers(PDO $databaseConnection): array
{
    $statement = $databaseConnection->query(
        'SELECT
            id_user,
            first_name,
            last_name,
            email,
            phone,
            role
         FROM users
         ORDER BY last_name ASC, first_name ASC'
    );

    $users = [];

    foreach ($statement->fetchAll() as $user) {
        $users[] = [
            'id_user' => (int) $user['id_user'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'role' => $user['role'],
        ];
    }

    return $users;
}

/**
 * Indique si une adresse email est déjà utilisée.
 */
function userEmailExists(
    PDO $databaseConnection,
    string $email
): bool {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM users
         WHERE email = :email'
    );

    $statement->execute([
        'email' => $email,
    ]);

    return (int) $statement->fetchColumn() > 0;
}

/**
 * Crée un nouvel utilisateur.
 *
 * @return int Identifiant du nouvel utilisateur.
 */
function createUser(
    PDO $databaseConnection,
    string $firstName,
    string $lastName,
    string $email,
    string $passwordHash,
    string $phone,
    string $role
): int {
    $statement = $databaseConnection->prepare(
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
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password_hash' => $passwordHash,
        'phone' => $phone,
        'role' => $role,
    ]);

    return (int) $databaseConnection->lastInsertId();
}

/**
 * Recherche un utilisateur à partir de son identifiant.
 *
 * Le hash du mot de passe n’est pas retourné.
 *
 * @return array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }|null
 */
function findUserById(
    PDO $databaseConnection,
    int $userId
): ?array {
    $statement = $databaseConnection->prepare(
        'SELECT
            id_user,
            first_name,
            last_name,
            email,
            phone,
            role
         FROM users
         WHERE id_user = :id_user
         LIMIT 1'
    );

    $statement->execute([
        'id_user' => $userId,
    ]);

    $user = $statement->fetch();

    if ($user === false) {
        return null;
    }

    return [
        'id_user' => (int) $user['id_user'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'phone' => $user['phone'],
        'role' => $user['role'],
    ];
}

/**
 * Indique si un email appartient à un autre utilisateur.
 */
function userEmailExistsForAnotherUser(
    PDO $databaseConnection,
    string $email,
    int $excludedUserId
): bool {
    $statement = $databaseConnection->prepare(
        'SELECT COUNT(*)
         FROM users
         WHERE email = :email
           AND id_user != :excluded_user_id'
    );

    $statement->execute([
        'email' => $email,
        'excluded_user_id' => $excludedUserId,
    ]);

    return (int) $statement->fetchColumn() > 0;
}

/**
 * Met à jour un utilisateur.
 *
 * Le mot de passe reste inchangé lorsque $passwordHash vaut null.
 */
function updateUser(
    PDO $databaseConnection,
    int $userId,
    string $firstName,
    string $lastName,
    string $email,
    string $phone,
    string $role,
    ?string $passwordHash
): void {
    if ($passwordHash === null) {
        $statement = $databaseConnection->prepare(
            'UPDATE users
             SET
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                role = :role
             WHERE id_user = :id_user'
        );

        $statement->execute([
            'id_user' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
        ]);

        return;
    }

    $statement = $databaseConnection->prepare(
        'UPDATE users
         SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            role = :role,
            password_hash = :password_hash
         WHERE id_user = :id_user'
    );

    $statement->execute([
        'id_user' => $userId,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'role' => $role,
        'password_hash' => $passwordHash,
    ]);
}