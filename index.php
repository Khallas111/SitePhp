<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';


$applicationName = 'Klaxon';
$description = 'Application de covoiturage inter-sites';


$databaseConnection = getDatabaseConnection();

$sql = '
    SELECT
        trips.id_trip,
        departure_agency.city AS departure_city,
        trips.departure_at,
        arrival_agency.city AS arrival_city,
        trips.arrival_at,
        trips.available_seats,
        trips.total_seats
    FROM trips
    INNER JOIN agencies AS departure_agency
        ON trips.departure_agency_id = departure_agency.id_agency
    INNER JOIN agencies AS arrival_agency
        ON trips.arrival_agency_id = arrival_agency.id_agency
    WHERE trips.available_seats > 0
        AND trips.departure_at > NOW()
    ORDER BY trips.departure_at ASC
';

$statement = $databaseConnection->query($sql);

$trips = $statement->fetchAll();

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $applicationName ?></title>
</head>

<body>
    <h1><?= $applicationName ?></h1>

    <p><?= $description ?></p>

    <h2>Prochain trajet</h2>

    <h2>Trajets planifiés</h2>

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