<?php

declare(strict_types=1);
$applicationName = 'Klaxon';
$description = 'Application de covoiturage inter-sites';

$trips = [
    [
        'departureAgency' => 'Perpignan',
        'arrivalAgency' => 'Montpellier',
        'departureDate' => '1er août 2026 à 08:30',
        'arrivalDate' => '1er août 2026 à 10:15',
        'totalSeats' => 4,
        'availableSeats' => 3,
        'isElectricVehicle' => true,
    ],
    [
        'departureAgency' => 'Montpellier',
        'arrivalAgency' => 'Toulouse',
        'departureDate' => '2 août 2026 à 09:00',
        'arrivalDate' => '2 août 2026 à 11:30',
        'totalSeats' => 5,
        'availableSeats' => 2,
        'isElectricVehicle' => false,
    ],
    [
        'departureAgency' => 'Toulouse',
        'arrivalAgency' => 'Perpignan',
        'departureDate' => '3 août 2026 à 14:00',
        'arrivalDate' => '3 août 2026 à 16:30',
        'totalSeats' => 4,
        'availableSeats' => 0,
        'isElectricVehicle' => false,
    ],
    [
        'departureAgency' => 'Narbonne',
        'arrivalAgency' => 'Perpignan',
        'departureDate' => '4 août 2026 à 07:45',
        'arrivalDate' => '4 août 2026 à 08:45',
        'totalSeats' => 4,
        'availableSeats' => 1,
        'isElectricVehicle' => true,
    ],
];






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

        <?php if ($trip['availableSeats'] > 0): ?>
            <article>
                <h3>
                    <?= htmlspecialchars($trip['departureAgency']) ?>
                    →
                    <?= htmlspecialchars($trip['arrivalAgency']) ?>
                </h3>

                <p>
                    Départ :
                    <?= htmlspecialchars($trip['departureDate']) ?>
                </p>

                <p>
                    Arrivée :
                    <?= htmlspecialchars($trip['arrivalDate']) ?>
                </p>

                <p>
                    Places disponibles :
                    <?= htmlspecialchars((string) $trip['availableSeats']) ?>
                    sur
                    <?= htmlspecialchars((string) $trip['totalSeats']) ?>
                </p>
            </article>

            <hr>
        <?php endif; ?>

    <?php endforeach; ?>
</body>

</html>