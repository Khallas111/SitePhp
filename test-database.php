<?php

declare(strict_types=1);

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/config/database.php';

try {
    $databaseConnection = getDatabaseConnection();

    echo '<p>Connexion à la base de données réussie.</p>';
} catch (PDOException $exception) {
    echo '<h1>Erreur de connexion</h1>';

    echo '<pre>';
    echo escape($exception->getMessage());
    echo '</pre>';
}