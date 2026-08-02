<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
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

            <a href="create-trip.php">
                Proposer un trajet
            </a>
        </nav>
    </header>

    <main></main>