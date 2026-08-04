<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var array<string, mixed>|null $currentUser
 */

require __DIR__ . '/../partials/header.php';

?>

<h1>Accès interdit</h1>

<p>
    Vous n’êtes pas autorisé à effectuer cette action.
</p>

<p>
    <a href="index.php">
        Retourner à l’accueil
    </a>
</p>

<?php

require __DIR__ . '/../partials/footer.php';

?>