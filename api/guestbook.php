<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

header('Content-Type: application/json');
require_once "../src/php/database.php";


function getUserIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    return $_SERVER['REMOTE_ADDR'];
}

// guestbook API

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!isset($id)) {
        http_response_code(200);
        $messages = $pdo->query("SELECT id, message, name, created_at FROM guestbook ORDER BY id DESC")->fetchAll();
        echo json_encode($messages);
        exit;
    }

    if ($id == 'latest') {         // Get the latest message
        http_response_code(200);
        $message = $pdo->query("SELECT id, message, name, created_at FROM guestbook ORDER BY id DESC LIMIT 1")->fetch();
        if (!$message) {
            http_response_code(404);
            echo json_encode(['error' => 'Aucun message trouvé', 'message' => $message]);
            exit;
        }
        echo json_encode($message);
        exit;
    }

    $message = $pdo->prepare("SELECT id, message, name, created_at FROM guestbook WHERE id = ? ORDER BY id DESC");
    $message->execute([(int)$id]);
    $message = $message->fetch();
    if (!$message) {
        http_response_code(404);
        echo json_encode(['error' => 'Message non trouvé', 'id' => $id]);
        exit;
    }
    echo json_encode($message);
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Expected JSON body:
    {
        "name": "John Doe",
        "message": "Hello, this is a message for the Butter God!"
    }
    */

    // check token

    $token = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : null;
    if (!$token || $token !== $_ENV['AUTH_TOKEN']) {   // temporary hardcoded token, should be stored securely in env variable or config file
        http_response_code(401);
        echo json_encode(['error' => 'Token d\'authentification invalide']);
        exit;
    }


    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['name']) || !isset($data['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Données manquantes']);
        exit;
    }

    $data['ip'] = getUserIP();

    $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, ip_address) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['message'], $data['ip']]);
    http_response_code(201);
    echo json_encode(['success' => 'Message ajouté avec succès', 'id' => $pdo->lastInsertId()]);
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete a message by ID (admin only)
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if (!isset($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID manquant']);
        exit;
    }

    // check token
    $token = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : null;
    if (!$token || $token !== $_ENV['AUTH_TOKEN']) {   // temporary hardcoded token, should be stored securely in env variable or config file
        http_response_code(401);
        echo json_encode(['error' => 'Token d\'authentification invalide']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM guestbook WHERE id = ?");
    $stmt->execute([(int)$id]);
    http_response_code(200);
    echo json_encode(['success' => 'Message supprimé avec succès', 'id' => $id]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}