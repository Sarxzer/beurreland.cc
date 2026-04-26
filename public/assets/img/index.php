<?php

$images = scandir(__DIR__);
$images = array_diff($images, ['.', '..', 'index.php']);

shuffle($images);
foreach ($images as $image) {
    $imagePath = "/assets/img/" . $image;

    $randSize = rand(50, 1000);
    
    $width = $randSize; // Set the width to the random size

    $html = "<a href=\"$imagePath\"><img src=\"$imagePath\" alt=\"Image\" style=\"width: {$width}px;\" loading=\"lazy\"></a>";

    echo $html;
}
