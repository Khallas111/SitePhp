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


    <div class="row g-3">

        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="firstName" class="form-label">
                    Prénom
                </label>

                <input type="text" id="firstName" name="firstName" class="form-control"
                    value="<?= escape($firstNameInput) ?>" autocomplete="given-name" required>
            </div>

        </div>

        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="lastName" class="form-label">

                    Nom
                </label>

                <input type="text" id="lastName" name="lastName" class="form-control"
                    value="<?= escape($lastNameInput) ?>" autocomplete="family-name" required>
            </div>
        </div>

    </div>




    <div class="row g-3">

        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label">
                    Adresse email
                </label>

                <input type="email" id="email" name="email" class="form-control" value="<?= escape($emailInput) ?>"
                    autocomplete="email" required>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="phone" class="form-label">
                    Téléphone
                </label>

                <input type="tel" id="phone" name="phone" class="form-control" value="<?= escape($phoneInput) ?>"
                    autocomplete="tel" required>
            </div>
        </div>

    </div>





    <div class="mb-3">
        <label for="role" class="form-label">
            Rôle
        </label>

        <select id="role" name="role" class="form-select" required>
            <option value="USER" <?php if ($roleInput === 'USER'): ?> selected <?php endif; ?>>
                Utilisateur
            </option>

            <option value="ADMIN" <?php if ($roleInput === 'ADMIN'): ?> selected <?php endif; ?>>
                Administrateur
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">
            Mot de passe
        </label>

        <input type="password" id="password" name="password" class="form-control" minlength="8"
            autocomplete="new-password" required>
    </div>

    <div class="mb-3">
        <label for="passwordConfirmation" class="form-label">
            Confirmer le mot de passe
        </label>

        <input type="password" id="passwordConfirmation" name="passwordConfirmation" class="form-control" minlength="8"
            autocomplete="new-password" required>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            Créer l’utilisateur
        </button>
</form>

<p>
    <a href="index.php?route=admin/users" class="btn btn-outline-secondary">
        Retour à la liste des utilisateurs
    </a>
</p>
</div>
<?php

require __DIR__ . '/../../partials/footer.php';

?>