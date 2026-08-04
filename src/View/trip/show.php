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
 * } $currentUser
 * @var array{
 *     id_trip: int,
 *     departure_city: string,
 *     departure_at: string,
 *     arrival_city: string,
 *     arrival_at: string,
 *     total_seats: int,
 *     available_seats: int,
 *     author_id: int,
 *     author_first_name: string,
 *     author_last_name: string,
 *     author_email: string,
 *     author_phone: string
 * } $trip
 * @var bool $isAuthor
 */

require __DIR__ . '/../partials/header.php';

?>

<h1>Détails du trajet</h1>

<section>
    <h2>
        <?= escape($trip['departure_city']) ?>
        →
        <?= escape($trip['arrival_city']) ?>
    </h2>

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
</section>

<section>
    <h2>Personne à contacter</h2>

    <p>
        <?= escape($trip['author_first_name']) ?>
        <?= escape($trip['author_last_name']) ?>
    </p>

    <p>
        Téléphone :
        <a href="tel:<?= escape($trip['author_phone']) ?>">
            <?= escape($trip['author_phone']) ?>
        </a>
    </p>

    <p>
        Adresse email :
        <a href="mailto:<?= escape($trip['author_email']) ?>">
            <?= escape($trip['author_email']) ?>
        </a>
    </p>
</section>

<?php if ($isAuthor): ?>
    <p>
        Vous êtes l’auteur de ce trajet.
    </p>
<?php endif; ?>

<p>
    <a href="index.php">
        Retour à la liste des trajets
    </a>
</p>

<?php

require __DIR__ . '/../partials/footer.php';

?>