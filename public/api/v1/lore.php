<?php
require_once __DIR__ . '/../../../src/php/init.php';
header('Content-Type: application/json');

// Lore API endpoint

// GET /api/v1/lore - returns the current lore text

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $loreFile = BASE_PATH . "/lore.txt";
    if (!file_exists($loreFile)) {
        http_response_code(500);
        echo json_encode(['error' => 'Lore file not found']);
        exit;
    }
    $lore = file_get_contents($loreFile);
    
    echo json_encode(['lore' => $lore]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}