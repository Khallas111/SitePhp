<?php

declare(strict_types=1);

/**
 * Affiche la liste des utilisateurs aux administrateurs.
 */
function showAdminUsersPage(
    PDO $databaseConnection
): void {
    $currentUser = requireAdmin();

    $applicationName = 'Klaxon';
    $pageTitle = 'Gestion des utilisateurs';
    $csrfToken = getCsrfToken();

    $users = findAllUsers($databaseConnection);

    require __DIR__ . '/../View/admin/users/index.php';
}