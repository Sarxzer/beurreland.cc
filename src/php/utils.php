<?php
// recurent functions used in multiple places

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/database.php';

$config = HTMLPurifier_Config::createDefault();

$config->set('HTML.Allowed', 'b,i,em,strong,a[href],p,br,ul,ol,li,blockquote,code');

$purifier = new HTMLPurifier($config);

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

/**
 * Check all messages from the same IP address in the last hours and return true if the count exceeds the limit
 * @param Pdo $pdo
 * @param string $ip IP address to check
 * @param int $limit max number of messages allowed
 * @param int $interval time interval in hours to check for messages
 * @return bool
 */
function isRateLimited(Pdo $pdo, string $ip, int $limit = 5, int $interval = 1): bool {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM guestbook WHERE ip_address = ? AND created_at > (NOW() - INTERVAL ? HOUR)");
    $stmt->execute([$ip, $interval]);
    return $stmt->fetchColumn() >= $limit; // Limit to 5 messages per hour
}


/**
 * Convert BBCode in the given text to HTML. Supported BBCode tags include:
 * [b], [i], [u], [s], [url], [code], [img], and [wave].
 * @param string $text
 * @return string
 */
function bbcode_to_html(string $text): string
{

    $bbcodes = [
        // Bold
        '/\[b\](.*?)\[\/b\]/is' => '<strong>$1</strong>',

        // Italic
        '/\[i\](.*?)\[\/i\]/is' => '<em>$1</em>',

        // Underline
        '/\[u\](.*?)\[\/u\]/is' => '<u>$1</u>',

        // Strike
        '/\[s\](.*?)\[\/s\]/is' => '<s>$1</s>',

        // URL [url]link[/url]
        '/\[url\](https?:\/\/.*?)\[\/url\]/is' => '<a href="$1" target="_blank" rel="noopener">$1</a>',

        // URL [url=link]text[/url]
        '/\[url=(https?:\/\/.*?)\](.*?)\[\/url\]/is' => '<a href="$1" target="_blank" rel="noopener">$2</a>',

        // Code (anything between [code] and [/code] is treated as preformatted text)
        '/\[code\](.*?)\[\/code\]/is' => '<pre><code>$1</code></pre>',

        // Image 
        '/\[img\](https?:\/\/.*?)\[\/img\]/is' => '<a href="$1" target="_blank" rel="noopener"><img src="$1" alt="image" /></a>',

        // Image with alt text [img=alt]url[/img]
        '/\[img=(.*?)\](https?:\/\/.*?)\[\/img\]/is'=> '<a href="$2" target="_blank" rel="noopener"><img src="$2" alt="$1" /></a>',

        // Wave
        '/\[wave\](.*?)\[\/wave\]/is' => '<span class="wave-auto">$1</span>',
    ];

    foreach ($bbcodes as $pattern => $replace) {
        $text = preg_replace($pattern, $replace, $text);
    }

    return nl2br($text);
}


/**
 * Sanitize user input by stripping out any HTML tags and encoding special characters to prevent XSS attacks. Using HTMLPurifier and also removing any non-ASCII characters to prevent unicode spam.
 * @param string $input
 * @return string
 */
function sanitize_input(string $input): string {
    global $purifier;

    // First, purify the input to remove any disallowed HTML tags and attributes
    $purified = $purifier->purify($input);

    // Then, strip out any remaining non-ASCII characters to prevent unicode spam
    $purified = preg_replace('/[^\x00-\x7F]/', '', $purified);
    return $purified;
}



/**
 * Handle custom error pages with error message
 * @param mixed $code
 * @param mixed $message
 * @return never
 */
function error_page($code, $message) {
    global $_baseDir;
    http_response_code($code);
    $_error_message = $message;
    require $_baseDir . "public/{$code}.php";
    exit;
}

/**
 * Convert DateTime to a more readable date format in french. Example: "12 janvier 2024 à 14:30"
 * @param DateTime $date
 * @return bool|string
 */
function french_date(DateTime $date) {
    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        "d MMMM y 'à' HH:mm"
    );

    return $formatter->format($date);
}

/**
 * Generate a CSRF token and store it in the session if it doesn't already exist. Return the token.
 * @return mixed|string
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify that the given CSRF token matches the one stored in the session. Return true if valid, false otherwise.
 * @param mixed|string $token
 * @return bool
 */
function verify_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}


/**
 * Check if the provided API key is valid by looking it up in the database. Return true if valid, false otherwise.
 * @param Pdo $pdo
 * @param string $key
 * @return bool
 */
function isKeyValid(Pdo $pdo, string $key): bool
{
    // check if the provided key matches the one in the new database table
    $stmt = $pdo->prepare("SELECT id FROM api_keys WHERE api_key = ?");
    $stmt->execute([$key]);
    return !!$stmt->fetch();
}

/**
 * Add a usage entry for the given API key in the database to track its usage. This should be called whenever an API key is used to access a protected endpoint.
 * @param Pdo $pdo
 * @param string $key
 * @param string $usageType
 * @return void
 */
function keyUsed(Pdo $pdo, string $key, string $usageType = 'general'): void
{
    // update the last_used timestamp of the key in the database
    $ip = getUserIP();
    $stmt = $pdo->prepare("INSERT INTO api_key_usage (api_key_id, used_type, ip_address) VALUES ((SELECT id FROM api_keys WHERE api_key = ?), ?, ?)");
    $stmt->execute([$key, $usageType, $ip]);
}
