<?php
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    error_page(403, 'Accès interdit');
    exit;
}

$pdo = new PDO("mysql:host={$_ENV['DATA_HOST']};dbname={$_ENV['DATA_NAME']};charset=utf8", $_ENV['DATA_USER'], $_ENV['DATA_PASS']);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

