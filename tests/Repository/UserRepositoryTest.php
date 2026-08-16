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

        $this->databaseConnection
            ->beginTransaction();

        $this->userRepository =
            new UserRepository(
                $this->databaseConnection
            );
    }

    protected function tearDown(): void
    {
        if (
            $this->databaseConnection
                ->inTransaction()
        ) {
            $this->databaseConnection
                ->rollBack();
        }
    }
    private function createUserFixture(
        string $email = 'user@example.test',
        string $role = 'USER',
        string $passwordHash = 'initial-hash'
    ): int {
        return $this->userRepository->create(
            'Jean',
            'Dupont',
            $email,
            $passwordHash,
            '0600000000',
            $role
        );
    }

    public function testCreateUser(): void
    {
        $userId =
            $this->userRepository->create(
                'Alice',
                'Martin',
                'alice@example.test',
                'test-hash',
                '0612345678',
                'USER'
            );

        self::assertGreaterThan(
            0,
            $userId
        );

        $user =
            $this->userRepository->findById(
                $userId
            );

        self::assertNotNull($user);

        self::assertSame(
            'Alice',
            $user['first_name']
        );

        self::assertSame(
            'Martin',
            $user['last_name']
        );

        self::assertSame(
            'alice@example.test',
            $user['email']
        );

        self::assertSame(
            '0612345678',
            $user['phone']
        );

        self::assertSame(
            'USER',
            $user['role']
        );
    }

    public function testFindByIdReturnsNullForUnknownUser(): void
    {
        $user =
            $this->userRepository->findById(
                999999
            );

        self::assertNull($user);
    }

    public function testFindByEmail(): void
    {
        $this->createUserFixture(
            'login@example.test',
            'USER',
            'my-password-hash'
        );

        $user =
            $this->userRepository->findByEmail(
                'login@example.test'
            );

        self::assertNotNull($user);

        self::assertSame(
            'login@example.test',
            $user['email']
        );

        self::assertSame(
            'my-password-hash',
            $user['password_hash']
        );
    }

    public function testFindByEmailReturnsNullForUnknownEmail(): void
    {
        $user =
            $this->userRepository->findByEmail(
                'unknown@example.test'
            );

        self::assertNull($user);
    }

    public function testEmailExists(): void
    {
        $this->createUserFixture(
            'existing@example.test'
        );

        self::assertTrue(
            $this->userRepository->emailExists(
                'existing@example.test'
            )
        );

        self::assertFalse(
            $this->userRepository->emailExists(
                'missing@example.test'
            )
        );
    }

    public function testFindAllUsers(): void
    {
        $this->createUserFixture(
            'first@example.test'
        );

        $this->createUserFixture(
            'second@example.test'
        );

        $this->createUserFixture(
            'third@example.test',
            'ADMIN'
        );

        $users =
            $this->userRepository->findAll();

        self::assertCount(
            3,
            $users
        );
    }

    public function testEmailExistsForAnotherUser(): void
    {
        $firstUserId =
            $this->createUserFixture(
                'first@example.test'
            );

        $this->createUserFixture(
            'second@example.test'
        );

        self::assertFalse(
            $this->userRepository
                ->emailExistsForAnotherUser(
                    'first@example.test',
                    $firstUserId
                )
        );

        self::assertTrue(
            $this->userRepository
                ->emailExistsForAnotherUser(
                    'second@example.test',
                    $firstUserId
                )
        );
    }

    public function testUpdateUserWithoutChangingPassword(): void
    {
        $userId =
            $this->createUserFixture(
                'before@example.test',
                'USER',
                'old-hash'
            );

        $this->userRepository->update(
            $userId,
            'Alice',
            'Martin',
            'after@example.test',
            '0699999999',
            'ADMIN',
            null
        );

        $user =
            $this->userRepository->findByEmail(
                'after@example.test'
            );

        self::assertNotNull($user);

        self::assertSame(
            'Alice',
            $user['first_name']
        );

        self::assertSame(
            'Martin',
            $user['last_name']
        );

        self::assertSame(
            '0699999999',
            $user['phone']
        );

        self::assertSame(
            'ADMIN',
            $user['role']
        );

        self::assertSame(
            'old-hash',
            $user['password_hash']
        );
    }

    public function testUpdateUserWithNewPassword(): void
    {
        $userId =
            $this->createUserFixture(
                'user@example.test',
                'USER',
                'old-hash'
            );

        $this->userRepository->update(
            $userId,
            'Jean',
            'Dupont',
            'user@example.test',
            '0600000000',
            'USER',
            'new-hash'
        );

        $user =
            $this->userRepository->findByEmail(
                'user@example.test'
            );

        self::assertNotNull($user);

        self::assertSame(
            'new-hash',
            $user['password_hash']
        );
    }

    public function testCountAdmins(): void
    {
        $this->createUserFixture(
            'admin1@example.test',
            'ADMIN'
        );

        $this->createUserFixture(
            'admin2@example.test',
            'ADMIN'
        );

        $this->createUserFixture(
            'user@example.test',
            'USER'
        );

        self::assertSame(
            2,
            $this->userRepository
                ->countAdmins()
        );
    }

    public function testDeleteUser(): void
    {
        $userId =
            $this->createUserFixture();

        self::assertNotNull(
            $this->userRepository->findById(
                $userId
            )
        );

        $this->userRepository->deleteById(
            $userId
        );

        self::assertNull(
            $this->userRepository->findById(
                $userId
            )
        );
    }
}