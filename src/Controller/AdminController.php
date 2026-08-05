<?php

declare(strict_types=1);

/**
 * Affiche la liste des utilisateurs aux administrateurs.
 */
function showAdminUsersPage(
    PDO $databaseConnection
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Gestion des utilisateurs';
    $csrfToken = getCsrfToken();

    $users = findAllUsers($databaseConnection);

    require __DIR__ . '/../View/admin/users/index.php';
}

/**
 * Affiche et traite le formulaire de création d’un utilisateur.
 */
function showAdminCreateUserPage(
    PDO $databaseConnection
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Créer un utilisateur';
    $csrfToken = getCsrfToken();

    $firstNameInput = '';
    $lastNameInput = '';
    $emailInput = '';
    $phoneInput = '';
    $roleInput = 'USER';

    $errors = [];
    $successMessage = '';

    if (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['created'] ?? '') === '1'
    ) {
        $successMessage =
            'L’utilisateur a été créé avec succès.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstNameInput = trim(
            $_POST['firstName'] ?? ''
        );

        $lastNameInput = trim(
            $_POST['lastName'] ?? ''
        );

        $emailInput = strtolower(
            trim($_POST['email'] ?? '')
        );

        $phoneInput = trim(
            $_POST['phone'] ?? ''
        );

        $roleInput = $_POST['role'] ?? '';

        $passwordInput =
            $_POST['password'] ?? '';

        $passwordConfirmationInput =
            $_POST['passwordConfirmation'] ?? '';

        if (
            !isCsrfTokenValid(
                $_POST['csrf_token'] ?? null
            )
        ) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if ($errors === []) {
            if ($firstNameInput === '') {
                $errors[] = 'Le prénom est obligatoire.';
            }

            if ($lastNameInput === '') {
                $errors[] = 'Le nom est obligatoire.';
            }

            if ($emailInput === '') {
                $errors[] =
                    'L’adresse email est obligatoire.';
            } elseif (
                filter_var(
                    $emailInput,
                    FILTER_VALIDATE_EMAIL
                ) === false
            ) {
                $errors[] =
                    'L’adresse email est invalide.';
            }

            if ($phoneInput === '') {
                $errors[] =
                    'Le numéro de téléphone est obligatoire.';
            }

            if (
                !in_array(
                    $roleInput,
                    ['USER', 'ADMIN'],
                    true
                )
            ) {
                $errors[] =
                    'Le rôle sélectionné est invalide.';
            }

            if ($passwordInput === '') {
                $errors[] =
                    'Le mot de passe est obligatoire.';
            } elseif (strlen($passwordInput) < 8) {
                $errors[] =
                    'Le mot de passe doit contenir '
                    . 'au moins 8 caractères.';
            }

            if (
                $passwordInput
                !== $passwordConfirmationInput
            ) {
                $errors[] =
                    'La confirmation du mot de passe '
                    . 'ne correspond pas.';
            }
        }

        if (
            $errors === []
            && userEmailExists(
                $databaseConnection,
                $emailInput
            )
        ) {
            $errors[] =
                'Cette adresse email est déjà utilisée.';
        }

        if ($errors === []) {
            $passwordHash = password_hash(
                $passwordInput,
                PASSWORD_DEFAULT
            );

            createUser(
                $databaseConnection,
                $firstNameInput,
                $lastNameInput,
                $emailInput,
                $passwordHash,
                $phoneInput,
                $roleInput
            );

            header(
                'Location: index.php'
                . '?route=admin/users/create'
                . '&created=1'
            );
            exit;
        }
    }

    require __DIR__
        . '/../View/admin/users/create.php';
}