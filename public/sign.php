<?php
session_start();

if (
    empty($_POST['csrf']) ||
    empty($_SESSION['csrf']) ||
    !hash_equals($_SESSION['csrf'], $_POST['csrf'])
) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

if (
    empty($_SERVER['HTTP_ORIGIN']) ||
    parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST) !== 'yourdomain.tld'
) {
    http_response_code(403);
    exit('Bad origin');
}

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

/**
 * Get the real IP address of the user, even if they are behind a proxy or using Cloudflare
 */
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

/**
 * Check the last 5 message from this IP, return True if the IP have sent 5 messages in the last hour
 * @param Pdo $pdo
 * @param string $ip
 * @return bool
 */
function isRateLimited($pdo, $ip) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM guestbook WHERE ip_address = ? AND created_at > (NOW() - INTERVAL 1 HOUR)");
    $stmt->execute([$ip]);
    return $stmt->fetchColumn() >= 5; // Limit to 5 messages per hour
}

if ($name && $message) {
    $ip = getUserIP();
    if (!isRateLimited($pdo, $ip)) {
        $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$name, $message, $ip]);
    } else {
        http_response_code(429);
        exit('Too many messages from this IP address. Please try again later.');
    }
}

header("Location: guestbook");