<?php
$file = '/var/www/data/counter.txt';

if (!file_exists($file)) {
    file_put_contents($file, "0");
}

if ($_GET["view"]) {
    $counter = (int)file_get_contents($file);
    echo $counter;
} elseif ($_GET["increment"]) {
    $counter = (int)file_get_contents($file);
    $counter++;
    echo $counter;
    file_put_contents($file, (string)$counter);
} else {
    echo "Invalid request";
}
?>