<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var string $successMessage
 * @var string $errorMessage
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 * @var list<array{
 *     id_agency: int|string,
 *     city: string
 * }> $agencies
 */

require __DIR__ . '/../../partials/header.php';

?>

<h1 class="mb-4">
    Gestion des agences
</h1>

<nav class="nav nav-pills gap-2 mb-4" aria-label="Navigation de l’administration">
    <a class="nav-link" href="index.php?route=admin/users">
        Utilisateurs
    </a>

    <a class="nav-link" href="index.php?route=admin/agencies">
        Agences
    </a>
</nav>

<?php if ($successMessage !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= escape($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($errorMessage !== ''): ?>
    <div class="alert alert-danger" role="alert">
        <?= escape($errorMessage) ?>
    </div>
<?php endif; ?>

<div class="d-flex flex-wrap
           justify-content-between
           align-items-center
           gap-3
           mb-4">
    <p class="mb-0">
        <?= escape((string) count($agencies)) ?>
        agence(s) enregistrée(s).
    </p>

    <a href="index.php?route=admin/agencies/create" class="btn btn-primary">
        Ajouter une agence
    </a>
</div>

<?php if ($agencies === []): ?>

    <div class="alert alert-info" role="status">
        Aucune agence n’est enregistrée.
    </div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-striped
                   table-hover align-middle">
            <caption>
                Liste des agences de Klaxon
            </caption>

            <thead>
                <tr>
                    <th scope="col">
                        ID
                    </th>

                    <th scope="col">
                        Ville
                    </th>

                    <th scope="col">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($agencies as $agency): ?>

                    <tr>
                        <td>
                            <?= escape(
                                (string) 
                                $agency['id_agency']
                            ) ?>
                        </td>

                        <td>
                            <strong>
                                <?= escape(
                                    $agency['city']
                                ) ?>
                            </strong>
                        </td>

                        <td>
                            <div class="d-flex
                                       flex-wrap
                                       gap-2">
                                <a href="index.php?route=admin/agencies/edit&amp;id=<?= escape(
                                    (string) 
                                    $agency['id_agency']
                                ) ?>" class="btn
                                           btn-warning
                                           btn-sm">
                                    Modifier
                                </a>

                                <form method="post" action="index.php?route=admin/agencies/delete" class="m-0"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer cette agence ?');">
                                    <input type="hidden" name="csrf_token" value="<?= escape(
                                        $csrfToken
                                    ) ?>">

                                    <input type="hidden" name="agency_id" value="<?= escape(
                                        (string) 
                                        $agency['id_agency']
                                    ) ?>">

                                    <button type="submit" class="btn
                                               btn-danger
                                               btn-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>

<p>
    <a href="index.php?route=admin/users">
        Gestion des utilisateurs
    </a>
</p>

<p>
    <a href="index.php">
        Retour à l’accueil
    </a>
</p>

<?php

require __DIR__ . '/../../partials/footer.php';

?>