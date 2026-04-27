<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = __DIR__ . '/../../../';

require $baseDir . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($baseDir);
$dotenv->load();

// small api wrapper to call klipy api from the frontend without exposing the key

$klipyKey = $_ENV['KLIPY_KEY'];


$type = $_GET['type'] ?? 'search';
$query = $_GET['query'] ??'';
$page = $_GET['page'] ?? 1;
$perPage = $_GET['per_page'] ?? 10;
$customerId = $_GET['customer_id'] ?? '';
$locale = $_GET['locale'] ?? 'FR';
$contentFilter = $_GET['content_filter'] ?? '';


if ($klipyKey == '') {
    http_response_code(500);
    echo json_encode(['error' => 'KLIPY_KEY not set']);
    exit;
}


if ($type === 'search') {


    if (!isset($_GET['query'])) {
        http_response_code(400);
        echo json_encode(['error' => 'query parameter is required']);
        exit;
    }
    $request = 'https://api.klipy.com/api/v1/' . $klipyKey . '/gifs/search?q=' . urlencode($query) . '&page=' . $page . '&per_page=' . $perPage . '&customer_id=' . $customerId . '&locale=' . $locale . '&content_filter=' . $contentFilter;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode != 200) {
        http_response_code($httpCode);
        echo json_encode(['error' => 'API request failed with status ' . $httpCode]);
        exit;
    }
    echo $response;
} elseif ($type === 'trending') {
    $request = 'https://api.klipy.com/api/v1/' . $klipyKey . '/gifs/trending?page=' . $page . '&per_page=' . $perPage . '&customer_id=' . $customerId . '&locale=' . $locale . '&content_filter=' . $contentFilter;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode != 200) {
        http_response_code($httpCode);
        echo json_encode(['error' => 'API request failed with status ' . $httpCode]);
        exit;
    }
    echo $response;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type parameter']);
    exit;
}
