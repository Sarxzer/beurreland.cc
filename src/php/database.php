<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Prevent direct access to this file
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$pdo = new PDO("mysql:host={$_ENV['DATA_HOST']};dbname={$_ENV['DATA_NAME']};charset=utf8", $_ENV['DATA_USER'], $_ENV['DATA_PASS']);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

