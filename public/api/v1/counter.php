<?php
// Counter api that increments a counter and returns the current value, with a simple frontend to display the counter value

// SQL table for counters:
// CREATE TABLE counters (
//     name VARCHAR(50) PRIMARY KEY,
//     value INT NOT NULL
// );
// UPDATE counters SET value = value + 1 WHERE name = 'visits';

require_once __DIR__ . '/../../../src/php/init.php';

header('Content-Type: application/json');

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
} else {
    error_page(405, 'Method Not Allowed');
}
