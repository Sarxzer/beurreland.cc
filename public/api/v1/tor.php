<?php

header('Content-Type: application/json');

$torHostname = file_get_contents("/var/www/html/hostname.txt");

echo json_encode(['hostname' => $torHostname]);