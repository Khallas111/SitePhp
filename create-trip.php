<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';

$departureAgencyIdInput = '';
$arrivalAgencyIdInput = '';
$totalSeatsInput = '';
$departureDateInput = '';
$arrivalDateInput = '';
$errors = [];
$successMessage = '';

$databaseConnection = getDatabaseConnection();

$agencyStatement = $databaseConnection->query(
    'SELECT id_agency, city
     FROM agencies
     ORDER BY city ASC'
);

$agencies = $agencyStatement->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departureAgencyIdInput = trim(
        $_POST['departureAgencyId'] ?? ''
    );

    $arrivalAgencyIdInput = trim(
        $_POST['arrivalAgencyId'] ?? ''
    );
    $totalSeatsInput = trim($_POST['totalSeats'] ?? '');
    $departureDateInput = trim($_POST['departureDate'] ?? '');
    $arrivalDateInput = trim($_POST['arrivalDate'] ?? '');

    $errors = validateTrip(
        $departureAgencyIdInput,
        $arrivalAgencyIdInput,
        $totalSeatsInput,
        $departureDateInput,
        $arrivalDateInput
    );

    if ($errors === []) {
        $totalSeats = (int) $totalSeatsInput;
        $successMessage = 'Le formulaire est valide.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Proposer un trajet</title>
</head>

<body>
    <h1>Proposer un trajet</h1>

    <?php if ($successMessage !== ''): ?>
        <p>
            <?= escape($successMessage) ?>
        </p>
    <?php endif; ?>

    <?php if ($errors !== []): ?>
        <div>
            <h2>Le formulaire contient des erreurs</h2>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?= escape($error) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div>


            <div>
                <label for="departureAgencyId">
                    Agence de départ
                </label>

                <select id="departureAgencyId" name="departureAgencyId" required>
                    <option value="">
                        Choisissez une agence
                    </option>

                    <?php foreach ($agencies as $agency): ?>
                        <option value="<?= escape((string) $agency['id_agency']) ?>" <?php if (
                               $departureAgencyIdInput
                               === (string) $agency['id_agency']
                           ): ?> selected <?php endif; ?>>
                            <?= escape($agency['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div>
            <div>
                <label for="arrivalAgencyId">
                    Agence d’arrivée
                </label>

                <select id="arrivalAgencyId" name="arrivalAgencyId" required>
                    <option value="">
                        Choisissez une agence
                    </option>

                    <?php foreach ($agencies as $agency): ?>
                        <option value="<?= escape((string) $agency['id_agency']) ?>" <?php if (
                               $arrivalAgencyIdInput
                               === (string) $agency['id_agency']
                           ): ?> selected <?php endif; ?>>
                            <?= escape($agency['city']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div>
            <label for="departureDate">Date et heure de départ</label>

            <input type="datetime-local" id="departureDate" name="departureDate"
                value="<?= escape($departureDateInput) ?>" required>
        </div>

        <div>
            <label for="arrivalDate">Date et heure d’arrivée</label>

            <input type="datetime-local" id="arrivalDate" name="arrivalDate" value="<?= escape($arrivalDateInput) ?>"
                required>
        </div>
        <div>
            <label for="totalSeats">Nombre total de places</label>

            <input type="number" id="totalSeats" name="totalSeats" min="1" value="<?= escape($totalSeatsInput) ?>"
                required>
        </div>

        <button type="submit">
            Créer le trajet
        </button>
    </form>
</body>

</html>