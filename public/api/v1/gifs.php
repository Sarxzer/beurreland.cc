<?php
require_once __DIR__ . '/../../../src/php/init.php';

header('Content-Type: application/json');

// small api wrapper to call klipy api from the frontend without exposing the key

$klipyKey = $_ENV['KLIPY_KEY'];


$type = $_GET['type'] ?? 'search';
$query = $_GET['query'] ??'';
$page = $_GET['page'] ?? 1;
$perPage = $_GET['per_page'] ?? 10;
$customerId = $_GET['customer_id'] ?? '';
$locale = $_GET['locale'] ?? 'FR';
$contentFilter = $_GET['content_filter'] ?? '';
// $injectButter = strtolower((string) ($_GET['beurre'] ?? 'false')) === 'true';
$injectButter = isset($_GET['beurre']) ? (int) $_GET['beurre'] : 0;


if ($klipyKey == '') {
    http_response_code(500);
    echo json_encode(['error' => 'KLIPY_KEY not set']);
    exit;
}

// Helper function to fetch butter gif
function getButterGif(String $klipyKey) {
    $butterRequest = 'https://api.klipy.com/api/v1/' . $klipyKey . '/gifs/items?ids=9226942027851934';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $butterRequest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if (isset($data['data']['data'][0]) && is_array($data['data']['data'][0])) {
            return $data['data']['data'][0];
        }
        if (isset($data['data'][0]) && is_array($data['data'][0])) {
            return $data['data'][0];
        }
    }
    return null;
}


function injectButterGifAtRandomPosition(mixed &$data, $butter) {
    if (!$butter) {
        return;
    }

    if (isset($data['data']['data']) && is_array($data['data']['data'])) {
        $target = &$data['data']['data'];
    } elseif (isset($data['data']) && is_array($data['data'])) {
        $target = &$data['data'];
    } else {
        return;
    }

    $insertAt = random_int(0, count($target));
    array_splice($target, $insertAt, 0, [$butter]);
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
    
    // Inject butter gif into response
    $data = json_decode($response, true);
    if ($injectButter) {
        $butter = getButterGif($klipyKey);
        for ($i = 0; $i < $injectButter; $i++) { // inject multiple butter gifs if the parameter is greater than 1
            injectButterGifAtRandomPosition($data, $butter);
        }
    }
    echo json_encode($data);
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
    
    // Inject butter gif into response
    $data = json_decode($response, true);
    if ($injectButter) {
        $butter = getButterGif($klipyKey);
        injectButterGifAtRandomPosition($data, $butter);
    }
    echo json_encode($data);
} elseif ($type === 'single') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'id parameter is required']);
        exit;
    }
    $request = 'https://api.klipy.com/api/v1/' . $klipyKey . '/gifs/items?ids=' . urlencode($_GET['id']);

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
    
    // Inject butter gif into response
    $data = json_decode($response, true);
    if ($injectButter) {
        $butter = getButterGif($klipyKey);
        injectButterGifAtRandomPosition($data, $butter);
    }
    echo json_encode($data);

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type parameter']);
    exit;
}
