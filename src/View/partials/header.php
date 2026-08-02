<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
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
</head>

<body>
    <header>
        <nav>
            <a href="index.php">
                <?= escape($applicationName) ?>
            </a>

            <?php if ($currentUser === null): ?>
                <a href="index.php?route=login">
                    Connexion
                </a>
            <?php else: ?>
                <a href="index.php?route=trips/create">
                    Proposer un trajet
                </a>

                <span>
                    <?= escape($currentUser['first_name']) ?>
                    <?= escape($currentUser['last_name']) ?>
                </span>

                <form method="post" action="index.php?route=logout">
                    <button type="submit">
                        Déconnexion
                    </button>
                </form>
            <?php endif; ?>
        </nav>
    </header>

    <main>