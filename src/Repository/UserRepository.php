<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class UserRepository
{
    public function __construct(
        private readonly PDO $databaseConnection
    ) {
    }
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
    public function findByEmail(
        string $email
    ): ?array {
        $statement = $this->databaseConnection->prepare(
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
    public function findAll(): array
    {
        $statement = $this->databaseConnection->query(
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
}