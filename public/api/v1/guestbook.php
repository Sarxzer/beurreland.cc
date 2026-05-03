<?php
$baseDir = __DIR__ . '/../../../';

require $baseDir . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($baseDir);
$dotenv->load();

$config = HTMLPurifier_Config::createDefault();

$config->set('HTML.Allowed', 'b,i,em,strong,a[href],p,br,ul,ol,li,blockquote,code');

$purifier = new HTMLPurifier($config);

header('Content-Type: application/json');

require_once $baseDir . '/src/php/database.php';
require_once $baseDir . '/src/php/utils.php';

// guestbook API

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    $html = isset($_GET['html']) && $_GET['html'] === 'true';

    if (!isset($id)) {
        http_response_code(200);
        $messages = $pdo->query("SELECT id, message, name, created_at FROM guestbook WHERE `status` != 'deleted' ORDER BY id DESC")->fetchAll();

        if ($html) {
            foreach ($messages as &$message) {
                $message['message'] = bbcode_to_html($message['message']);
            }
        }

        echo json_encode($messages);

        //keyUsed($pdo, $token, 'guestbook_list');

        exit;
    }

    if ($id == 'latest') {         // Get the latest message
        http_response_code(200);
        $message = $pdo->query("SELECT id, message, name, created_at FROM guestbook WHERE `status` != 'deleted' ORDER BY id DESC LIMIT 1")->fetch();
        if (!$message) {
            http_response_code(404);
            echo json_encode(['error' => 'Aucun message trouvé', 'message' => $message]);
            exit;
        }
        if ($html) {
            $message['message'] = bbcode_to_html($message['message']);
        }

        echo json_encode($message);

        //keyUsed($pdo, $token, 'guestbook_latest');

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
    if ($html) {
        $message['message'] = bbcode_to_html($message['message']);
    }

    echo json_encode($message);

    // keyUsed($pdo, $token, 'guestbook_single');

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
    // if (!$token || $token !== $_ENV['AUTH_TOKEN']) {   // temporary hardcoded token, should be stored securely in env variable or config file
    //     http_response_code(401);
    //     echo json_encode(['error' => 'Token d\'authentification invalide']);
    //     exit;
    // }

    if (!$token || !isKeyValid($pdo, $token)) {
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

    $name = substr(trim($data['name']), 0, 50);
    $message = substr(trim($data['message']), 0, 1000);

    $name = $purifier->purify($name);
    $message = $purifier->purify($message);

    $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, ip_address) VALUES (?, ?, ?)");
    $stmt->execute([$name, $message, $data['ip']]);
    http_response_code(201);

    echo json_encode(['success' => 'Message ajouté avec succès', 'id' => $pdo->lastInsertId()]);

    keyUsed($pdo, $token, 'guestbook_post');
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
    // if (!$token || $token !== $_ENV['AUTH_TOKEN']) {   // temporary hardcoded token, should be stored securely in env variable or config file
    //     http_response_code(401);
    //     echo json_encode(['error' => 'Token d\'authentification invalide']);
    //     exit;
    // }

    if (!$token || !isKeyValid($pdo, $token)) {
        http_response_code(401);
        echo json_encode(['error' => 'Token d\'authentification invalide']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE guestbook SET `status` = 'deleted' WHERE id = ?");
    $stmt->execute([(int)$id]);
    http_response_code(200);

    echo json_encode(['success' => 'Message supprimé avec succès', 'id' => $id]);

    keyUsed($pdo, $token, 'guestbook_delete');

    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}
