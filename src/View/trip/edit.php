<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var string $successMessage
 * @var list<string> $errors
 * @var list<array<string, mixed>> $agencies
 * @var string $departureAgencyIdInput
 * @var string $arrivalAgencyIdInput
 * @var string $departureDateInput
 * @var string $arrivalDateInput
 * @var string $totalSeatsInput
 */

require __DIR__ . '/../partials/header.php';

?>

<h1 class="mb-4">
    Modifier un trajet
</h1>

<?php if ($successMessage !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= escape($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger" role="alert">
        <h2 class="h5">
            Le formulaire contient des erreurs
        </h2>

        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= escape($error) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-4">

        <form method="post">

            <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <label for="departureAgencyId" class="form-label">
                        Agence de départ
                    </label>

                    <select id="departureAgencyId" name="departureAgencyId" class="form-select" required>
                        <option value="">
                            Choisissez une agence
                        </option>

                        <?php foreach ($agencies as $agency): ?>
                            <option value="<?= escape(
                                (string) $agency['id_agency']
                            ) ?>" <?php if (
                                 $departureAgencyIdInput
                                 === (string) $agency['id_agency']
                             ): ?> selected
                                <?php endif; ?>>
                                <?= escape($agency['city']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="arrivalAgencyId" class="form-label">
                        Agence d’arrivée
                    </label>

                    <select id="arrivalAgencyId" name="arrivalAgencyId" class="form-select" required>
                        <option value="">
                            Choisissez une agence
                        </option>

                        <?php foreach ($agencies as $agency): ?>
                            <option value="<?= escape(
                                (string) $agency['id_agency']
                            ) ?>" <?php if (
                                 $arrivalAgencyIdInput
                                 === (string) $agency['id_agency']
                             ): ?> selected
                                <?php endif; ?>>
                                <?= escape($agency['city']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="departureDate" class="form-label">
                        Date et heure de départ
                    </label>

                    <input type="datetime-local" id="departureDate" name="departureDate" class="form-control"
                        value="<?= escape($departureDateInput) ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="arrivalDate" class="form-label">
                        Date et heure d’arrivée
                    </label>

                    <input type="datetime-local" id="arrivalDate" name="arrivalDate" class="form-control"
                        value="<?= escape($arrivalDateInput) ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="totalSeats" class="form-label">
                        Nombre total de places
                    </label>

                    <input type="number" id="totalSeats" name="totalSeats" class="form-control" min="1"
                        value="<?= escape($totalSeatsInput) ?>" required>

                    <div class="form-text">
                        Indiquez le nombre total de places
                        proposées dans le véhicule.
                    </div>
                </div>

            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    Enregistrer les modifications
                </button>

                <a href="index.php" class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>

        </form>

    </div>
</div>

<?php

require __DIR__ . '/../partials/footer.php';

?>
