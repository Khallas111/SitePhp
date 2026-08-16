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

<h1 class="mb-4">
    Connexion
</h1>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">

        <div class="card shadow-sm">
            <div class="card-body p-4">

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

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Adresse email
                        </label>

                        <input type="email" id="email" name="email" class="form-control"
                            value="<?= escape($emailInput) ?>" autocomplete="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Mot de passe
                        </label>

                        <input type="password" id="password" name="password" class="form-control"
                            autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Se connecter
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<?php

require __DIR__ . '/../partials/footer.php';

?>