<?php
require_once 'src/php/database.php';

$name = trim($_POST['name']);
$message = trim($_POST['message']);

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