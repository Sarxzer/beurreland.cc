<?php

require_once '../vendor/autoload.php';

$config = HTMLPurifier_Config::createDefault();

$config->set('HTML.Allowed', 'b,i,em,strong,a[href],p,br,ul,ol,li,blockquote,code');

$purifier = new HTMLPurifier($config);

require_once '../src/php/database.php';

$name = trim($_POST['name']);
$message = trim($_POST['message']);

$name = $purifier->purify($name);
$message = $purifier->purify($message);

// $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
// $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

$name = substr($name, 0, 50); // Limit name to 50 characters after sanitization
$message = substr($message, 0, 1000); // Limit message to 1000 characters after sanitization

function getUserIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    return $_SERVER['REMOTE_ADDR'];
}

if (!empty($_POST['website'])) {
    // Honeypot field filled, likely a bot
    header("Location: guestbook");
    exit;
}

if ($name && $message) {
    $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, ip_address) VALUES (?, ?, ?)");
    $stmt->execute([$name, $message, getUserIP()]);
}

header("Location: guestbook");