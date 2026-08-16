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
                                (string) $trip['id_trip']
                            ) ?>" data-bs-toggle="modal" data-bs-target="#tripModal<?= escape(
                                 (string) $trip['id_trip']
                             ) ?>">
                                Voir les détails
                            </a>
                        </div>
                    <?php endif; ?>

                </article>
            </div>

        <?php endforeach; ?>

    </div>

    <?php if ($currentUser !== null): ?>

        <?php foreach ($trips as $trip): ?>

            <div class="modal fade" id="tripModal<?= escape(
                (string) $trip['id_trip']
            ) ?>" tabindex="-1" aria-labelledby="tripModalLabel<?= escape(
                 (string) $trip['id_trip']
             ) ?>" aria-hidden="true">
                <div class="modal-dialog
                       modal-dialog-centered
                       modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">

                            <h2 class="modal-title fs-5" id="tripModalLabel<?= escape(
                                (string) $trip['id_trip']
                            ) ?>">
                                <?= escape(
                                    $trip['departure_city']
                                ) ?>

                                <span aria-hidden="true">
                                    →
                                </span>

                                <?= escape(
                                    $trip['arrival_city']
                                ) ?>
                            </h2>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>

                        </div>

                        <div class="modal-body">

                            <section class="mb-4">

                                <h3 class="h6">
                                    Informations du trajet
                                </h3>

                                <dl class="row mb-0">

                                    <dt class="col-sm-5">
                                        Départ
                                    </dt>

                                    <dd class="col-sm-7">
                                        <?= escape(
                                            formatDateTime(
                                                $trip[
                                                    'departure_at'
                                                ]
                                            )
                                        ) ?>
                                    </dd>

                                    <dt class="col-sm-5">
                                        Arrivée
                                    </dt>

                                    <dd class="col-sm-7">
                                        <?= escape(
                                            formatDateTime(
                                                $trip[
                                                    'arrival_at'
                                                ]
                                            )
                                        ) ?>
                                    </dd>

                                    <dt class="col-sm-5">
                                        Places disponibles
                                    </dt>

                                    <dd class="col-sm-7">
                                        <?= escape(
                                            (string) 
                                            $trip[
                                                'available_seats'
                                            ]
                                        ) ?>

                                        sur

                                        <?= escape(
                                            (string) 
                                            $trip['total_seats']
                                        ) ?>
                                    </dd>

                                </dl>

                            </section>

                            <section>

                                <h3 class="h6">
                                    Personne à contacter
                                </h3>

                                <p class="mb-2">
                                    <strong>
                                        <?= escape(
                                            $trip[
                                                'author_first_name'
                                            ]
                                        ) ?>

                                        <?= escape(
                                            $trip[
                                                'author_last_name'
                                            ]
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

                            </section>

                        </div>

                        <div class="modal-footer">

                            <a class="btn btn-outline-primary" href="index.php?route=trips/show&amp;id=<?= escape(
                                (string) $trip['id_trip']
                            ) ?>">
                                Ouvrir la fiche complète
                            </a>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Fermer
                            </button>

                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>

<?php

require __DIR__ . '/partials/footer.php';

?>