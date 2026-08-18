<?php

declare(strict_types=1);

/**
 * Régénère docs/mcd.png avec l’extension GD de PHP.
 */

$image = imagecreatetruecolor(1400, 900);

if ($image === false) {
    throw new RuntimeException('Impossible de créer l’image du MCD.');
}

$surface = imagecolorallocate($image, 241, 248, 252);
$primary = imagecolorallocate($image, 0, 116, 199);
$deepBlue = imagecolorallocate($image, 0, 73, 124);
$text = imagecolorallocate($image, 56, 64, 80);
$danger = imagecolorallocate($image, 205, 44, 46);
$success = imagecolorallocate($image, 130, 184, 100);
$white = imagecolorallocate($image, 255, 255, 255);

imagefill($image, 0, 0, $surface);
imagestring($image, 5, 50, 35, 'KLAXON - MODELE CONCEPTUEL DE DONNEES', $deepBlue);
imagestring($image, 3, 50, 62, 'Covoiturage inter-sites', $text);

/**
 * Dessine une entité et ses propriétés.
 *
 * @param list<string> $attributes
 */
function drawEntity(
    GdImage $image,
    int $x,
    int $y,
    int $width,
    string $title,
    array $attributes,
    int $headerColor,
    int $borderColor,
    int $surfaceColor,
    int $textColor,
    int $whiteColor
): void {
    $height = 64 + count($attributes) * 28;

    imagefilledrectangle(
        $image,
        $x,
        $y,
        $x + $width,
        $y + $height,
        $surfaceColor
    );
    imagerectangle(
        $image,
        $x,
        $y,
        $x + $width,
        $y + $height,
        $borderColor
    );
    imagefilledrectangle(
        $image,
        $x,
        $y,
        $x + $width,
        $y + 46,
        $headerColor
    );
    imagestring($image, 5, $x + 16, $y + 15, $title, $whiteColor);

    foreach ($attributes as $index => $attribute) {
        imagestring(
            $image,
            4,
            $x + 16,
            $y + 58 + $index * 28,
            $attribute,
            $textColor
        );
    }
}

/**
 * Dessine une association métier.
 */
function drawAssociation(
    GdImage $image,
    int $centerX,
    int $centerY,
    string $label,
    int $color,
    int $whiteColor
): void {
    imagefilledellipse($image, $centerX, $centerY, 190, 68, $color);
    imageellipse($image, $centerX, $centerY, 190, 68, $color);
    imagestring(
        $image,
        4,
        $centerX - (int) (strlen($label) * 4),
        $centerY - 8,
        $label,
        $whiteColor
    );
}

drawEntity(
    $image,
    60,
    170,
    360,
    'EMPLOYE',
    [
        '# id_user',
        'first_name',
        'last_name',
        'email (unique)',
        'password_hash',
        'phone',
        'role',
    ],
    $deepBlue,
    $deepBlue,
    $white,
    $text,
    $white
);

drawEntity(
    $image,
    980,
    210,
    350,
    'AGENCE',
    [
        '# id_agency',
        'city (unique)',
    ],
    $primary,
    $primary,
    $white,
    $text,
    $white
);

drawEntity(
    $image,
    500,
    560,
    410,
    'TRAJET',
    [
        '# id_trip',
        'departure_at',
        'arrival_at',
        'total_seats',
        'available_seats',
    ],
    $success,
    $deepBlue,
    $white,
    $text,
    $white
);

drawAssociation($image, 480, 365, 'PROPOSER', $deepBlue, $white);
drawAssociation($image, 905, 435, 'PARTIR DE', $primary, $white);
drawAssociation($image, 1110, 560, 'ARRIVER A', $danger, $white);

imageline($image, 420, 310, 390, 350, $deepBlue);
imageline($image, 547, 392, 620, 560, $deepBlue);
imagestring($image, 4, 390, 320, '0,N', $text);
imagestring($image, 4, 560, 470, '1,1', $text);

imageline($image, 835, 455, 780, 560, $primary);
imageline($image, 973, 407, 1030, 340, $primary);
imagestring($image, 4, 795, 490, '1,1', $text);
imagestring($image, 4, 990, 375, '0,N', $text);

imageline($image, 1008, 570, 910, 650, $danger);
imageline($image, 1140, 526, 1160, 330, $danger);
imagestring($image, 4, 940, 600, '1,1', $text);
imagestring($image, 4, 1160, 430, '0,N', $text);

imagestring(
    $image,
    3,
    60,
    845,
    'Regles : agence de depart != agence d arrivee ; arrivee > depart ; places disponibles <= places totales.',
    $text
);

$outputPath = __DIR__ . '/mcd.png';

if (!imagepng($image, $outputPath)) {
    throw new RuntimeException('Impossible d’enregistrer le MCD.');
}

imagedestroy($image);

echo $outputPath . PHP_EOL;
