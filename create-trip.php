<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';

$departureAgency = '';
$arrivalAgency = '';
$totalSeatsInput = '';
$departureDateInput = '';
$arrivalDateInput = '';
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departureAgency = trim($_POST['departureAgency'] ?? '');
    $arrivalAgency = trim($_POST['arrivalAgency'] ?? '');
    $totalSeatsInput = trim($_POST['totalSeats'] ?? '');
    $departureDateInput = trim($_POST['departureDate'] ?? '');
    $arrivalDateInput = trim($_POST['arrivalDate'] ?? '');

    $errors = validateTrip(
        $departureAgency,
        $arrivalAgency,
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
            <label for="departureAgency">Agence de départ</label>

            <input type="text" id="departureAgency" name="departureAgency" value="<?= escape($departureAgency) ?>"
                required>


        </div>

        <div>
            <label for="arrivalAgency">Agence d’arrivée</label>

            <input type="text" id="arrivalAgency" name="arrivalAgency" value="<?= escape($arrivalAgency) ?>" required>
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