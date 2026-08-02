<?php

declare(strict_types=1);

/**
 * Affiche et traite le formulaire de connexion.
 */
function showLoginPage(PDO $databaseConnection): void
{
    $applicationName = 'Klaxon';
    $pageTitle = 'Connexion';

    $emailInput = '';
    $errors = [];

    $currentUser = getCurrentUser();
    $csrfToken = getCsrfToken();

    if ($currentUser !== null) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $emailInput = trim($_POST['email'] ?? '');
        $passwordInput = $_POST['password'] ?? '';



        if (!isCsrfTokenValid($_POST['csrf_token'] ?? null)) {
            $errors[] =
                'Le formulaire a expiré. Veuillez réessayer.';
        }

        if ($errors === []) {
            if ($emailInput === '') {
                $errors[] = 'L’adresse email est obligatoire.';
            } elseif (
                filter_var(
                    $emailInput,
                    FILTER_VALIDATE_EMAIL
                ) === false
            ) {
                $errors[] = 'L’adresse email est invalide.';
            }

            if ($passwordInput === '') {
                $errors[] = 'Le mot de passe est obligatoire.';
            }
        }

        if ($errors === []) {
            $user = findUserByEmail(
                $databaseConnection,
                $emailInput
            );

            if (
                $user === null
                || !password_verify(
                    $passwordInput,
                    $user['password_hash']
                )
            ) {
                $errors[] = 'Adresse email ou mot de passe incorrect.';
            }
        }

        if ($errors === [] && $user !== null) {
            session_regenerate_id(true);
            unset($_SESSION['csrf_token']);

            $_SESSION['user'] = [
                'id_user' => (int) $user['id_user'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];

            header('Location: index.php');
            exit;
        }
    }

    require __DIR__ . '/../View/auth/login.php';
}

/**
 * Déconnecte l’utilisateur courant.
 */
function logoutUser(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);

        echo 'Méthode non autorisée.';
        return;
    }

    if (!isCsrfTokenValid($_POST['csrf_token'] ?? null)) {
        http_response_code(403);

        echo 'Requête interdite.';
        return;
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $cookieParameters = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $cookieParameters['path'],
            $cookieParameters['domain'],
            $cookieParameters['secure'],
            $cookieParameters['httponly']
        );
    }

    session_destroy();

    header('Location: index.php');
    exit;
}