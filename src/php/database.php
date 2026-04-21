<?php
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    include __DIR__ . '/../../403.php';
    exit;
}

$pdo = new PDO("mysql:host={$_ENV['DATA_HOST']};dbname={$_ENV['DATA_NAME']};charset=utf8", $_ENV['DATA_USER'], $_ENV['DATA_PASS']);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

