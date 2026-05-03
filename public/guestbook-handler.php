<?php
require_once __DIR__ . '/../src/php/init.php';

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    // http_response_code(403);
    // exit('Invalid CSRF token');
    //use cutom 403 error page
    $error_message = 'Token CSRF invalide. Veuillez réessayer.';
    require '403.php';
    exit;
}

if (
    empty($_SERVER['HTTP_ORIGIN']) ||
    parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST) !== 'beurreland.cc'
) {
    // http_response_code(403);
    // exit('Invalid origin');
    //use custom 403 error page
    $error_message = 'Origine invalide. Veuillez réessayer.';
    require '403.php';
    exit;
}


$name = trim($_POST['name']);
$message = trim($_POST['message']);

$name = sanitize_input($name);
$message = sanitize_input($message);

$name = substr($name, 0, 50); // Limit name to 50 characters after sanitization
$message = substr($message, 0, 1000); // Limit message to 1000 characters after sanitization

if (!empty($_POST['website'])) {
    // Honeypot field filled, likely a bot
    header("Location: guestbook");
    exit;
}

if ($name && $message) {
    $ip = getUserIP();
    if (!isRateLimited($pdo, $ip)) {
        $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$name, $message, $ip]);
    } else {
        // http_response_code(429);
        // exit('Too many messages from this IP address. Please try again later.');
        //use custom 429 error page
        error_page(429, 'Trop de messages de cette adresse IP. Veuillez réessayer plus tard.');
        exit;
    }
}

header("Location: guestbook");