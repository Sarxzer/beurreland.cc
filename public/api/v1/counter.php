<?php
// Counter api that increments a counter and returns the current value, with a simple frontend to display the counter value

// SQL table for counters:
// CREATE TABLE counters (
//     name VARCHAR(50) PRIMARY KEY,
//     value INT NOT NULL
// );
// UPDATE counters SET value = value + 1 WHERE name = 'visits';

$baseDir = __DIR__ . '/../../../';

require_once $baseDir . '/src/php/database.php';
require_once $baseDir . '/src/php/utils.php';

header('Content-Type: application/json');

function incrementCounter()
{
    global $pdo;

    // increment the counter in the database
    $stmt = $pdo->prepare('UPDATE counters SET value = value + 1 WHERE name = "visits"');
    $stmt->execute();
}

function getCounter()
{
    global $pdo;

    // get the current counter value from the database
    $stmt = $pdo->prepare('SELECT value FROM counters WHERE name = "visits"');
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo getCounter();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : null;

    if (empty($token) || !isKeyValid($pdo, $token)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    incrementCounter();
    $counter = getCounter();
    echo json_encode(['value' => $counter]);

    keyUsed($pdo, $token, 'counter_increment');
    exit;
}
