<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var string $firstNameInput
 * @var string $lastNameInput
 * @var string $emailInput
 * @var string $phoneInput
 * @var string $roleInput
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

<h1>Modifier un utilisateur</h1>

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

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

    <div>
        <label for="firstName">
            Prénom
        </label>

        <input type="text" id="firstName" name="firstName" value="<?= escape($firstNameInput) ?>"
            autocomplete="given-name" required>
    </div>

    <div>
        <label for="lastName">
            Nom
        </label>

        <input type="text" id="lastName" name="lastName" value="<?= escape($lastNameInput) ?>"
            autocomplete="family-name" required>
    </div>

    <div>
        <label for="email">
            Adresse email
        </label>

        <input type="email" id="email" name="email" value="<?= escape($emailInput) ?>" autocomplete="email" required>
    </div>

    <div>
        <label for="phone">
            Téléphone
        </label>

        <input type="tel" id="phone" name="phone" value="<?= escape($phoneInput) ?>" autocomplete="tel" required>
    </div>

    <div>
        <label for="role">
            Rôle
        </label>

        <select id="role" name="role" required>
            <option value="USER" <?php if ($roleInput === 'USER'): ?> selected <?php endif; ?>>
                Utilisateur
            </option>

            <option value="ADMIN" <?php if ($roleInput === 'ADMIN'): ?> selected <?php endif; ?>>
                Administrateur
            </option>
        </select>
    </div>

    <fieldset>
        <legend>Changer le mot de passe</legend>

        <p>
            Laissez les deux champs vides pour conserver
            le mot de passe actuel.
        </p>

        <div>
            <label for="password">
                Nouveau mot de passe
            </label>

            <input type="password" id="password" name="password" minlength="8" autocomplete="new-password">
        </div>

        <div>
            <label for="passwordConfirmation">
                Confirmer le nouveau mot de passe
            </label>

            <input type="password" id="passwordConfirmation" name="passwordConfirmation" minlength="8"
                autocomplete="new-password">
        </div>
    </fieldset>

    <button type="submit">
        Enregistrer les modifications
    </button>
</form>

<p>
    <a href="index.php?route=admin/users">
        Retour à la liste des utilisateurs
    </a>
</p>

<?php

require __DIR__ . '/../../partials/footer.php';

?>