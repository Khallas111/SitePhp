<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Repository\UserRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class UserRepositoryTest extends TestCase
{
    private PDO $databaseConnection;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->databaseConnection =
            \getTestDatabaseConnection();

        $this->databaseConnection->beginTransaction();

        $this->userRepository =
            new UserRepository(
                $this->databaseConnection
            );
    }

    protected function tearDown(): void
    {
        if ($this->databaseConnection->inTransaction()) {
            $this->databaseConnection->rollBack();
        }
    }

    /**
     * Ajoute une donnée RH de test sans exposer d’écriture
     * dans le dépôt applicatif des utilisateurs.
     */
    private function createUserFixture(
        string $firstName,
        string $lastName,
        string $email,
        string $role = 'USER'
    ): void {
        $statement = $this->databaseConnection->prepare(
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
            'password_hash' => 'test-hash',
            'phone' => '0600000000',
            'role' => $role,
        ]);
    }

    public function testFindByEmail(): void
    {
        $this->createUserFixture(
            'Alice',
            'Martin',
            'alice@example.test',
            'ADMIN'
        );

        $user = $this->userRepository->findByEmail(
            'alice@example.test'
        );

        self::assertNotNull($user);
        self::assertSame('Alice', $user['first_name']);
        self::assertSame('ADMIN', $user['role']);
        self::assertSame('test-hash', $user['password_hash']);
    }

    public function testFindByEmailReturnsNullForUnknownEmail(): void
    {
        self::assertNull(
            $this->userRepository->findByEmail(
                'unknown@example.test'
            )
        );
    }

    public function testFindAllUsersSortedByName(): void
    {
        $this->createUserFixture(
            'Zoé',
            'Martin',
            'zoe@example.test'
        );
        $this->createUserFixture(
            'Alice',
            'Durand',
            'alice@example.test',
            'ADMIN'
        );

        $users = $this->userRepository->findAll();

        self::assertCount(2, $users);
        self::assertSame('Durand', $users[0]['last_name']);
        self::assertArrayNotHasKey('password_hash', $users[0]);
    }
}
