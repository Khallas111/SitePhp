<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $successMessage
 * @var list<string> $errors
 * @var list<array<string, mixed>> $agencies
 * @var string $departureAgencyIdInput
 * @var string $arrivalAgencyIdInput
 * @var string $departureDateInput
 * @var string $arrivalDateInput
 * @var string $totalSeatsInput
 * @var string $csrfToken
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 */

require __DIR__ . '/../partials/header.php';

?>



<h1 class="mb-4">
    Proposer un trajet
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

            <fieldset class="mb-4">
                <legend class="h5 mb-3">
                    Personne à contacter
                </legend>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="contactName" class="form-label">
                            Nom et prénom
                        </label>
                        <input type="text" id="contactName" class="form-control" value="<?= escape(
                            $currentUser['first_name']
                            . ' '
                            . $currentUser['last_name']
                        ) ?>" readonly>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="contactPhone" class="form-label">
                            Téléphone
                        </label>
                        <input type="text" id="contactPhone" class="form-control"
                            value="<?= escape($currentUser['phone']) ?>" readonly>
                    </div>

                    <div class="col-12">
                        <label for="contactEmail" class="form-label">
                            Adresse email
                        </label>
                        <input type="email" id="contactEmail" class="form-control"
                            value="<?= escape($currentUser['email']) ?>" readonly>
                    </div>
                </div>
            </fieldset>

            <h2 class="h5 mb-3">
                Informations du trajet
            </h2>

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
                        min="<?= escape((new DateTimeImmutable())->format('Y-m-d\TH:i')) ?>"
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
                    Créer le trajet
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
