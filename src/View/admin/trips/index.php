<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var string $successMessage
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 * @var list<array{
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
 * }> $trips
 */

require __DIR__ . '/../../partials/header.php';

$now = new DateTimeImmutable();

?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="mb-1">
            Gestion des trajets
        </h1>
        <p class="text-body-secondary mb-0">
            Tous les trajets, y compris les trajets passés et complets.
        </p>
    </div>

    <a class="btn btn-primary" href="index.php?route=trips/create">
        Créer un trajet
    </a>
</div>

<?php if ($successMessage !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= escape($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($trips === []): ?>
    <div class="alert alert-info" role="status">
        Aucun trajet n’est enregistré.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <caption>
                <?= escape((string) count($trips)) ?> trajet(s) enregistré(s)
            </caption>
            <thead>
                <tr>
                    <th scope="col">Trajet</th>
                    <th scope="col">Départ</th>
                    <th scope="col">Arrivée</th>
                    <th scope="col">Places</th>
                    <th scope="col">Contact</th>
                    <th scope="col">État</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trips as $trip): ?>
                    <?php
                    $departure = new DateTimeImmutable(
                        $trip['departure_at']
                    );
                    ?>
                    <tr>
                        <td>
                            <strong>
                                <?= escape($trip['departure_city']) ?>
                                <span aria-hidden="true">→</span>
                                <?= escape($trip['arrival_city']) ?>
                            </strong>
                        </td>
                        <td>
                            <?= escape(formatDateTime($trip['departure_at'])) ?>
                        </td>
                        <td>
                            <?= escape(formatDateTime($trip['arrival_at'])) ?>
                        </td>
                        <td>
                            <?= escape((string) $trip['available_seats']) ?>
                            / <?= escape((string) $trip['total_seats']) ?>
                        </td>
                        <td>
                            <?= escape($trip['author_first_name']) ?>
                            <?= escape($trip['author_last_name']) ?>
                        </td>
                        <td>
                            <?php if ($departure <= $now): ?>
                                <span class="badge text-bg-secondary">Passé</span>
                            <?php elseif ($trip['available_seats'] === 0): ?>
                                <span class="badge text-bg-danger">Complet</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-outline-secondary btn-sm" href="index.php?route=trips/show&amp;id=<?= escape(
                                    (string) $trip['id_trip']
                                ) ?>">
                                    Consulter
                                </a>

                                <a class="btn btn-outline-primary btn-sm" href="index.php?route=trips/edit&amp;id=<?= escape(
                                    (string) $trip['id_trip']
                                ) ?>">
                                    Modifier
                                </a>

                                <form method="post" action="index.php?route=trips/delete" class="m-0"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
                                    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">
                                    <input type="hidden" name="trip_id" value="<?= escape(
                                        (string) $trip['id_trip']
                                    ) ?>">
                                    <input type="hidden" name="return_to" value="admin/trips">

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php

require __DIR__ . '/../../partials/footer.php';

?>
