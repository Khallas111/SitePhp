<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }|null $currentUser
 */

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= escape($pageTitle) ?>
        -
        <?= escape($applicationName) ?>
    </title>

    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand bg-body-tertiary border-bottom">
            <div class="container d-flex flex-column flex-md-row gap-3">
                <a class="navbar-brand fw-bold" href="index.php">
                    <?= escape($applicationName) ?>
                </a>

                <div class="d-flex flex-column flex-md-row
                           align-items-md-center gap-2 ms-md-auto">
                    <a class="nav-link" href="index.php">
                        Accueil
                    </a>

                    <?php if ($currentUser === null): ?>
                        <a class="btn btn-primary" href="index.php?route=login">
                            Connexion
                        </a>
                    <?php else: ?>

                        <a class="nav-link" href="index.php?route=trips/create">
                            Proposer un trajet
                        </a>

                        <?php if (
                            $currentUser['role'] === 'ADMIN'
                        ): ?>
                            <a class="nav-link" href="index.php?route=admin/users">
                                Utilisateurs
                            </a>

                            <a class="nav-link" href="index.php?route=admin/agencies">
                                Agences
                            </a>
                        <?php endif; ?>

                        <span class="navbar-text">
                            <?= escape(
                                $currentUser['first_name']
                            ) ?>
                            <?= escape(
                                $currentUser['last_name']
                            ) ?>
                        </span>

                        <form method="post" action="index.php?route=logout" class="m-0">
                            <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

                            <button type="submit" class="btn btn-outline-secondary">
                                Déconnexion
                            </button>
                        </form>

                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-4">