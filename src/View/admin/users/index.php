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

<h1>Gestion des utilisateurs</h1>

<p>
    <a href="index.php?route=admin/users/create">
        Ajouter un utilisateur
    </a>
</p>

<p>
    <?= escape((string) count($users)) ?>
    utilisateur(s) enregistré(s).
</p>

<?php if ($users === []): ?>
    <p>Aucun utilisateur n’est enregistré.</p>
<?php else: ?>
    <table>
        <caption>
            Liste des utilisateurs de Klaxon
        </caption>

        <thead>
            <tr>
                <th scope="col">Identifiant</th>
                <th scope="col">Nom</th>
                <th scope="col">Adresse email</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Rôle</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?= escape((string) $user['id_user']) ?>
                    </td>

                    <td>
                        <?= escape($user['first_name']) ?>
                        <?= escape($user['last_name']) ?>

                        <?php if (
                            $user['id_user']
                            === $currentUser['id_user']
                        ): ?>
                            <strong>(vous)</strong>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="mailto:<?= escape($user['email']) ?>">
                            <?= escape($user['email']) ?>
                        </a>
                    </td>

                    <td>
                        <a href="tel:<?= escape($user['phone']) ?>">
                            <?= escape($user['phone']) ?>
                        </a>
                    </td>

                    <td>
                        <?= escape($user['role']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p>
    <a href="index.php">
        Retour à l’accueil
    </a>
</p>

<?php

require __DIR__ . '/../../partials/footer.php';

?>