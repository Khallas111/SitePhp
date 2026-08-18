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
 * @var bool $canManageTrip
 */

require __DIR__ . '/../partials/header.php';

?>

<h1 class="mb-4">
    Détails du trajet
</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h2 class="card-title">
            <?= escape($trip['departure_city']) ?>
            →
            <?= escape($trip['arrival_city']) ?>
        </h2>

        <dl class="row mb-0">

            <dt class="col-sm-4">
                Départ
            </dt>

            <dd class="col-sm-8">
                <?= escape(
                    formatDateTime(
                        $trip['departure_at']
                    )
                ) ?>
            </dd>

            <dt class="col-sm-4">
                Arrivée
            </dt>

            <dd class="col-sm-8">
                <?= escape(
                    formatDateTime(
                        $trip['arrival_at']
                    )
                ) ?>
            </dd>

            <dt class="col-sm-4">
                Places disponibles
            </dt>

            <dd class="col-sm-8">
                <?= escape(
                    (string) $trip['available_seats']
                ) ?>
                /
                <?= escape(
                    (string) $trip['total_seats']
                ) ?>
            </dd>

        </dl>

    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h2 class="h4">
            Personne à contacter
        </h2>

        <p class="mb-2">
            <strong>
                <?= escape(
                    $trip['author_first_name']
                ) ?>

                <?= escape(
                    $trip['author_last_name']
                ) ?>
            </strong>
        </p>

        <p class="mb-2">
            Téléphone :

            <a href="tel:<?= escape(
                $trip['author_phone']
            ) ?>">
                <?= escape(
                    $trip['author_phone']
                ) ?>
            </a>
        </p>

        <p class="mb-0">
            Email :

            <a href="mailto:<?= escape(
                $trip['author_email']
            ) ?>">
                <?= escape(
                    $trip['author_email']
                ) ?>
            </a>
        </p>

    </div>
</div>

<?php if ($isAuthor): ?>
    <p>
        Vous êtes l’auteur de ce trajet.
    </p>
<?php endif; ?>

<?php if ($canManageTrip): ?>

    <div class="d-flex flex-wrap gap-2 mb-4">

        <a class="btn btn-outline-primary" href="index.php?route=trips/edit&amp;id=<?= escape(
            (string) $trip['id_trip']
        ) ?>">
            Modifier le trajet
        </a>

        <form method="post" action="index.php?route=trips/delete"
            onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
            <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

            <input type="hidden" name="trip_id" value="<?= escape(
                (string) $trip['id_trip']
            ) ?>">

            <button type="submit" class="btn btn-danger">
                Supprimer le trajet
            </button>
        </form>

    </div>

<?php endif; ?>

<p>
    <a class="btn btn-outline-secondary" href="index.php">
        Retour aux trajets
    </a>
</p>

<?php

require __DIR__ . '/../partials/footer.php';

?>
