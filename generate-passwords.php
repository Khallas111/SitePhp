<?php

declare(strict_types=1);

$adminPasswordHash = password_hash('Admin123!', PASSWORD_DEFAULT);
$userPasswordHash = password_hash('User123!', PASSWORD_DEFAULT);

echo '<h1>Hashes de démonstration</h1>';

echo '<p>Administrateur :</p>';
echo '<pre>' . htmlspecialchars($adminPasswordHash) . '</pre>';

echo '<p>Utilisateur :</p>';
echo '<pre>' . htmlspecialchars($userPasswordHash) . '</pre>';