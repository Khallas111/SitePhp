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

<h1>Modifier le trajet</h1>

<?php if ($successMessage !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= escape($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div
        class="alert alert-danger"
        role="alert"
    >
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

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

    <div>
        <label for="departureAgencyId">
            Agence de départ
        </label>

        <select id="departureAgencyId" name="departureAgencyId" required>
            <option value="">
                Choisissez une agence
            </option>

            <?php foreach ($agencies as $agency): ?>
                <option value="<?= escape(
                    (string) $agency['id_agency']
                ) ?>" <?php if (
                     $departureAgencyIdInput
                     === (string) $agency['id_agency']
                 ): ?>
                        selected
                    <?php endif; ?>
                    >
                    <?= escape($agency['city']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="arrivalAgencyId">
            Agence d’arrivée
        </label>

        <select id="arrivalAgencyId" name="arrivalAgencyId" required>
            <option value="">
                Choisissez une agence
            </option>

            <?php foreach ($agencies as $agency): ?>
                <option value="<?= escape(
                    (string) $agency['id_agency']
                ) ?>" <?php if (
                     $arrivalAgencyIdInput
                     === (string) $agency['id_agency']
                 ): ?>
                        selected
                    <?php endif; ?>
                    >
                    <?= escape($agency['city']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="departureDate">
            Date et heure de départ
        </label>

        <input type="datetime-local" id="departureDate" name="departureDate" value="<?= escape($departureDateInput) ?>"
            required>
    </div>

    <div>
        <label for="arrivalDate">
            Date et heure d’arrivée
        </label>

        <input type="datetime-local" id="arrivalDate" name="arrivalDate" value="<?= escape($arrivalDateInput) ?>"
            required>
    </div>

    <div>
        <label for="totalSeats">
            Nombre total de places
        </label>

        <input type="number" id="totalSeats" name="totalSeats" min="1" value="<?= escape($totalSeatsInput) ?>" required>
    </div>

    <button type="submit">
        Enregistrer les modifications
    </button>
</form>

<p>
    <a href="index.php">
        Annuler et revenir à l’accueil
    </a>
</p>

<?php

require __DIR__ . '/../partials/footer.php';

?>