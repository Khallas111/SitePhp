<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Repository/TripRepository.php';

$applicationName = 'Klaxon';
$description = 'Application de covoiturage inter-sites';

$databaseConnection = getDatabaseConnection();

$trips = findAvailableFutureTrips($databaseConnection);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= escape($applicationName) ?>
    </title>
</head>

<body>
    <h1>
        <?= escape($applicationName) ?>
    </h1>

    <p>
        <?= escape($description) ?>
    </p>

    <h2>Trajets planifiés</h2>

    <?php if ($trips === []): ?>
        <p>Aucun trajet disponible pour le moment.</p>
    <?php else: ?>
        <?php foreach ($trips as $trip): ?>
            <article>
                <h3>
                    <?= escape($trip['departure_city']) ?>
                    →
                    <?= escape($trip['arrival_city']) ?>
                </h3>

                <p>
                    Départ :
                    <?= escape(formatDateTime($trip['departure_at'])) ?>
                </p>

                <p>
                    Arrivée :
                    <?= escape(formatDateTime($trip['arrival_at'])) ?>
                </p>

                <p>
                    Places disponibles :
                    <?= escape((string) $trip['available_seats']) ?>
                    sur
                    <?= escape((string) $trip['total_seats']) ?>
                </p>
            </article>

            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>