<?php
include __DIR__ . '/../src/php/utils.php';

$file = '/var/www/data/counter.txt';

$fp = fopen($file, 'c+');

if (!$fp) {
    http_response_code(500);
    exit(include "500.php");
}

flock($fp, LOCK_EX);

$counter = (int)trim(stream_get_contents($fp));

if ($_GET["view"]) {
    echo $counter;
} elseif ($_GET["increment"]) {
    $counter++;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $counter);
    echo $counter;
} else {
    error_page(400, 'Bad Request: Missing "view" or "increment" parameter.');
}

flock($fp, LOCK_UN);
fclose($fp);