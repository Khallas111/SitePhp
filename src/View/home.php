<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $description
 * @var list<array<string, mixed>> $trips
 */

require __DIR__ . '/partials/header.php';

?>

<h1><?= escape($applicationName) ?></h1>

<p><?= escape($description) ?></p>

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
                <?= escape(
                    formatDateTime($trip['departure_at'])
                ) ?>
            </p>

            <p>
                Arrivée :
                <?= escape(
                    formatDateTime($trip['arrival_at'])
                ) ?>
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

<?php

require __DIR__ . '/partials/footer.php';

?>