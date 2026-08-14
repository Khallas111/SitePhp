<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $emailInput
 * @var list<string> $errors
 * @var null $currentUser
 * @var string $csrfToken
 */

require __DIR__ . '/../partials/header.php';

?>

<h1>Connexion</h1>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger" role="alert">
        <h2 class="h5">
            La connexion a échoué
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
        <label for="email">
            Adresse email
        </label>

        <input type="email" id="email" name="email" value="<?= escape($emailInput) ?>" autocomplete="email" required>
    </div>

    <div>
        <label for="password">
            Mot de passe
        </label>

        <input type="password" id="password" name="password" autocomplete="current-password" required>
    </div>

    <button type="submit">
        Se connecter
    </button>
</form>

<?php

require __DIR__ . '/../partials/footer.php';

?>