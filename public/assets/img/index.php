<?php

$images = scandir(__DIR__);
$images = array_diff($images, ['.', '..', 'index.php']);

shuffle($images);
foreach ($images as $image) {
    $imagePath = "/assets/img/" . $image;
    $scale = rand(10, 100) / 100; // Random scale between 0.1 and 2.0

    $width = getimagesize(__DIR__ . '/' . $image)[0] * $scale;
    $height = getimagesize(__DIR__ . '/' . $image)[1] * $scale;

    $html = "<img src=\"$imagePath\" alt=\"Image, scale: $scale\" style=\"width: {$width}px; height: {$height}px;\">";

    echo $html;
}
