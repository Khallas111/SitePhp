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

<h1>Créer un utilisateur</h1>

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
            <option value="USER" <?php if ($roleInput === 'USER'): ?>
                    selected
                <?php endif; ?>
                >
                Utilisateur
            </option>

            <option value="ADMIN" <?php if ($roleInput === 'ADMIN'): ?>
                    selected
                <?php endif; ?>
                >
                Administrateur
            </option>
        </select>
    </div>

    <div>
        <label for="password">
            Mot de passe
        </label>

        <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
    </div>

    <div>
        <label for="passwordConfirmation">
            Confirmer le mot de passe
        </label>

        <input type="password" id="passwordConfirmation" name="passwordConfirmation" minlength="8"
            autocomplete="new-password" required>
    </div>

    <button type="submit">
        Créer l’utilisateur
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