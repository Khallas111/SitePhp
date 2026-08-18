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
 */

require __DIR__ . '/../../partials/header.php';

?>

<h1 class="mb-4">
    Utilisateurs
</h1>

<p class="text-body-secondary mb-4">
    Les employés proviennent du système RH et sont disponibles en lecture seule.
</p>

<div class="d-flex flex-wrap
           justify-content-between
           align-items-center
           gap-3
           mb-4">
    <p class="mb-0">
        <?= escape((string) count($users)) ?>
        utilisateur(s) enregistré(s).
    </p>
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
                Liste des employés
            </caption>

            <thead>
                <tr>
                    <th scope="col">
                        ID
                    </th>

                    <th scope="col">
                        Employé
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
                                <span class="badge
                                       text-bg-secondary">
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
                            <?= escape(
                                $user['phone']
                            ) ?>
                        </td>

                        <td>
                            <?php if (
                                $user['role'] === 'ADMIN'
                            ): ?>
                                <span class="badge
                                       text-bg-primary">
                                    Administrateur
                                </span>
                            <?php else: ?>
                                <span class="badge
                                       text-bg-secondary">
                                    Utilisateur
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php

require __DIR__ . '/../../partials/footer.php';

?>
