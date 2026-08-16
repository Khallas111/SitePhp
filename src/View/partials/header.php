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
        <nav class="navbar navbar-expand-lg
               bg-body-tertiary border-bottom">
            <div class="container">

                <a class="navbar-brand fw-bold" href="index.php">
                    <?= escape($applicationName) ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Afficher la navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto
                           mb-2 mb-lg-0
                           align-items-lg-center">

                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                Accueil
                            </a>
                        </li>

                        <?php if ($currentUser === null): ?>

                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-primary" href="index.php?route=login">
                                    Connexion
                                </a>
                            </li>

                        <?php else: ?>

                            <li class="nav-item">
                                <a class="nav-link" href="index.php?route=trips/create">
                                    Proposer un trajet
                                </a>
                            </li>

                            <?php if (
                                $currentUser['role'] === 'ADMIN'
                            ): ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?route=admin/users">
                                        Utilisateurs
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?route=admin/agencies">
                                        Agences
                                    </a>
                                </li>

                            <?php endif; ?>

                            <li class="nav-item">
                                <span class="navbar-text px-lg-3">
                                    <?= escape(
                                        $currentUser['first_name']
                                    ) ?>

                                    <?= escape(
                                        $currentUser['last_name']
                                    ) ?>
                                </span>
                            </li>

                            <li class="nav-item">
                                <form method="post" action="index.php?route=logout" class="m-0">
                                    <input type="hidden" name="csrf_token" value="<?= escape(
                                        $csrfToken
                                    ) ?>">

                                    <button type="submit" class="btn
                                           btn-outline-secondary">
                                        Déconnexion
                                    </button>
                                </form>
                            </li>

                        <?php endif; ?>

                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <main class="container py-4">