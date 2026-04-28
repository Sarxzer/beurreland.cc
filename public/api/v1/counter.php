<?php
// Counter api that increments a counter and returns the current value, with a simple frontend to display the counter value

// SQL table for counters:
// CREATE TABLE counters (
//     name VARCHAR(50) PRIMARY KEY,
//     value INT NOT NULL
// );
// UPDATE counters SET value = value + 1 WHERE name = 'visits';

$baseDir = __DIR__ . '/../../../';

include $baseDir . '/src/php/database.php';

header('Content-Type: application/json');

function isKeyValid($pdo, $key)
{
    // check if the provided key matches the one in the new database table
    $stmt = $pdo->prepare("SELECT id FROM api_keys WHERE api_key = ?");
    $stmt->execute([$key]);
    return !!$stmt->fetch();
}

function keyUsed($pdo, $key, $usageType = 'guestbook')
{
    // update the last_used timestamp of the key in the database
    $ip = getUserIP();
    $stmt = $pdo->prepare("INSERT INTO api_key_usage (api_key_id, used_type, ip_address) VALUES ((SELECT id FROM api_keys WHERE api_key = ?), ?, ?)");
    $stmt->execute([$key, $usageType, $ip]);
}

function getUserIP()
{
    // get the user's IP address, accounting for proxies
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

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