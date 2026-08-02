<?php

declare(strict_types=1);

/**
 * Affiche une page indiquant que la ressource demandée
 * n’a pas été trouvée.
 */
function showNotFoundPage(): void
{
    http_response_code(404);

    $applicationName = 'Klaxon';
    $pageTitle = 'Page introuvable';

    require __DIR__ . '/../View/errors/404.php';
}