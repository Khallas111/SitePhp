<?php

/**
 * @var string $applicationName
 * @var string $pageTitle
 * @var string $csrfToken
 * @var array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * } $currentUser
 * @var list<array{
 *     id_user: int,
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     phone: string,
 *     role: string
 * }> $users
 * @var string $successMessage
 * @var string $errorMessage
 */

require __DIR__ . '/../../partials/header.php';

?>

<h1 class="mb-4">
    Gestion des utilisateurs
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
        <?= escape((string) count($users)) ?>
        utilisateur(s) enregistré(s).
    </p>

    <a href="index.php?route=admin/users/create" class="btn btn-primary">
        Ajouter un utilisateur
    </a>
</div>

<?php if ($users === []): ?>

    <div class="alert alert-info" role="status">
        Aucun utilisateur n’est enregistré.
    </div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-striped
                   table-hover align-middle">
            <caption>
                Liste des utilisateurs de Klaxon
            </caption>

            <thead>
                <tr>
                    <th scope="col">
                        ID
                    </th>

                    <th scope="col">
                        Utilisateur
                    </th>

                    <th scope="col">
                        Email
                    </th>

                    <th scope="col">
                        Téléphone
                    </th>

                    <th scope="col">
                        Rôle
                    </th>

                    <th scope="col">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($users as $user): ?>

                    <tr>
                        <td>
                            <?= escape(
                                (string) $user['id_user']
                            ) ?>
                        </td>

                        <td>
                            <strong>
                                <?= escape(
                                    $user['first_name']
                                ) ?>

                                <?= escape(
                                    $user['last_name']
                                ) ?>
                            </strong>

                            <?php if (
                                $user['id_user']
                                === $currentUser['id_user']
                            ): ?>
                                <span class="badge text-bg-secondary">
                                    Vous
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="mailto:<?= escape(
                                $user['email']
                            ) ?>">
                                <?= escape(
                                    $user['email']
                                ) ?>
                            </a>
                        </td>

                        <td>
                            <a href="tel:<?= escape(
                                $user['phone']
                            ) ?>" class="text-nowrap">
                                <?= escape(
                                    $user['phone']
                                ) ?>
                            </a>
                        </td>

                        <td>
                            <?php if (
                                $user['role'] === 'ADMIN'
                            ): ?>
                                <span class="badge text-bg-primary">
                                    Administrateur
                                </span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">
                                    Utilisateur
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex
                                       flex-wrap
                                       gap-2">
                                <a href="index.php?route=admin/users/edit&amp;id=<?= escape(
                                    (string) 
                                    $user['id_user']
                                ) ?>" class="btn
                                           btn-warning
                                           btn-sm">
                                    Modifier
                                </a>

                                <?php if (
                                    $user['id_user']
                                    !== $currentUser['id_user']
                                ): ?>

                                    <form method="post" action="index.php?route=admin/users/delete" class="m-0"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                        <input type="hidden" name="csrf_token" value="<?= escape(
                                            $csrfToken
                                        ) ?>">

                                        <input type="hidden" name="user_id" value="<?= escape(
                                            (string) 
                                            $user['id_user']
                                        ) ?>">

                                        <button type="submit" class="btn
                                                   btn-danger
                                                   btn-sm">
                                            Supprimer
                                        </button>
                                    </form>

                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>

<p>
    <a href="index.php?route=admin/agencies">
        Gestion des agences
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