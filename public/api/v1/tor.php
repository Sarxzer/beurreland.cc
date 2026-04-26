<?php

$torHostname = file_get_contents("/var/www/html/hostname.txt");

echo $torHostname;