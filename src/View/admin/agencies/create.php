<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var string $cityInput
 * @var string $successMessage
 * @var list<string> $errors
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 */

require __DIR__ . '/../../partials/header.php';

?>

<h1>Ajouter une agence</h1>

<?php if ($successMessage !== ''): ?>
    <p>
        <?= escape($successMessage) ?>
    </p>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div>
        <h2>
            Le formulaire contient des erreurs
        </h2>

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
    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

    <div>
        <label for="city">
            Ville
        </label>

        <input type="text" id="city" name="city" value="<?= escape($cityInput) ?>" autocomplete="address-level2"
            required>
    </div>

    <button type="submit">
        Ajouter l’agence
    </button>
</form>

<p>
    <a href="index.php?route=admin/agencies">
        Retour à la liste des agences
    </a>
</p>

<?php

require __DIR__ . '/../../partials/footer.php';

?>