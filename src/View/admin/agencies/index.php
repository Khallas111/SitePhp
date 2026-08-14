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
 *     id_agency: int|string,
 *     city: string
 * }> $agencies
 */

require __DIR__ . '/../../partials/header.php';

?>

<h1>Gestion des agences</h1>

<p>
    <a href="index.php?route=admin/agencies/create">
        Ajouter une agence
    </a>
</p>

<p>
    <?= escape((string) count($agencies)) ?>
    agence(s) enregistrée(s).
</p>

<?php if ($agencies === []): ?>
    <p>Aucune agence n’est enregistrée.</p>
<?php else: ?>
    <table>
        <caption>
            Liste des agences de Klaxon
        </caption>

        <thead>
            <tr>
                <th scope="col">
                    Identifiant
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
                            (string) $agency['id_agency']
                        ) ?>
                    </td>

                    <td>
                        <?= escape($agency['city']) ?>
                    </td>

                    <td>
                        <a href="index.php?route=admin/agencies/edit&amp;id=<?= escape(
                            (string) $agency['id_agency']
                        ) ?>">
                            Modifier
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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