<?php

declare(strict_types=1);

$departureAgency = '';
$arrivalAgency = '';
$totalSeatsInput = '';
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departureAgency = trim($_POST['departureAgency'] ?? '');
    $arrivalAgency = trim($_POST['arrivalAgency'] ?? '');
    $totalSeatsInput = trim($_POST['totalSeats'] ?? '');

    if ($departureAgency === '') {
        $errors[] = 'L’agence de départ est obligatoire.';
    }

    if ($arrivalAgency === '') {
        $errors[] = 'L’agence d’arrivée est obligatoire.';
    }

    if (
        $departureAgency !== ''
        && $arrivalAgency !== ''
        && $departureAgency === $arrivalAgency
    ) {
        $errors[] = 'Les agences de départ et d’arrivée doivent être différentes.';
    }

    if ($totalSeatsInput === '') {
        $errors[] = 'Le nombre total de places est obligatoire.';
    } elseif (!ctype_digit($totalSeatsInput)) {
        $errors[] = 'Le nombre total de places doit être un nombre entier.';
    } elseif ((int) $totalSeatsInput < 1) {
        $errors[] = 'Le nombre total de places doit être supérieur à zéro.';
    }

    if ($errors === []) {
        $totalSeats = (int) $totalSeatsInput;
        $successMessage = 'Le formulaire est valide.';

        $departureAgency = '';
        $arrivalAgency = '';
        $totalSeatsInput = '';
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
            <?= htmlspecialchars($successMessage) ?>
        </p>
    <?php endif; ?>

    <?php if ($errors !== []): ?>
        <div>
            <h2>Le formulaire contient des erreurs</h2>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="departureAgency">Agence de départ</label>

            <input type="text" id="departureAgency" name="departureAgency"
                value="<?= htmlspecialchars($departureAgency) ?>" required>


        </div>

        <div>
            <label for="arrivalAgency">Agence d’arrivée</label>

            <input type="text" id="arrivalAgency" name="arrivalAgency" value="<?= htmlspecialchars($arrivalAgency) ?>"
                required>
        </div>

        <div>
            <label for="totalSeats">Nombre total de places</label>

            <input type="number" id="totalSeats" name="totalSeats" min="1"
                value="<?= htmlspecialchars($totalSeatsInput) ?>" required>
        </div>

        <button type="submit">
            Créer le trajet
        </button>
    </form>
</body>

</html>