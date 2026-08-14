<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $description
 * @var list<array<string, mixed>> $trips
 *   @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }|null $currentUser
 * @var string $successMessage
 */



require __DIR__ . '/partials/header.php';

?>

<section class="mb-5">
    <h1 class="display-5 fw-bold">
        <?= escape($applicationName) ?>
    </h1>

    <p class="lead">
        <?= escape($description) ?>
    </p>
</section>

<h2 class="mb-4">
    Trajets disponibles
</h2>

<?php if ($successMessage !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= escape($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($trips === []): ?>

    <div class="alert alert-info" role="status">
        Aucun trajet disponible pour le moment.
    </div>

<?php else: ?>

    <div class="row g-4">

        <?php foreach ($trips as $trip): ?>

            <div class="col-12 col-md-6 col-xl-4">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">

                        <h3 class="card-title h5">
                            <?= escape(
                                $trip['departure_city']
                            ) ?>

                            <span aria-hidden="true">
                                →
                            </span>

                            <?= escape(
                                $trip['arrival_city']
                            ) ?>
                        </h3>

                        <p class="card-text">
                            <strong>Départ :</strong><br>

                            <?= escape(
                                formatDateTime(
                                    $trip['departure_at']
                                )
                            ) ?>
                        </p>

                        <p class="card-text">
                            <strong>Arrivée :</strong><br>

                            <?= escape(
                                formatDateTime(
                                    $trip['arrival_at']
                                )
                            ) ?>
                        </p>

                        <p class="card-text">
                            <strong>
                                Places disponibles :
                            </strong>

                            <?= escape(
                                (string) 
                                $trip['available_seats']
                            ) ?>
                        </p>

                    </div>

                    <?php if ($currentUser !== null): ?>
                        <div class="card-footer bg-transparent">
                            <a class="btn btn-primary w-100" href="index.php?route=trips/show&amp;id=<?= escape(
                                (string) 
                                $trip['id_trip']
                            ) ?>">
                                Voir les détails
                            </a>
                        </div>
                    <?php endif; ?>

                </article>
            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php

require __DIR__ . '/partials/footer.php';

?>