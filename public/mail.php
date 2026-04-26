<?php
require_once '../vendor/autoload.php';

$config = HTMLPurifier_Config::createDefault();

$config->set('HTML.Allowed', 'b,i,em,strong,a[href],p,br,ul,ol,li,blockquote,code');

$purifier = new HTMLPurifier($config);

session_start();

include '../src/php/mail.php';

if (empty($_POST['name']) || empty($_POST['message'])) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    $error_message = "Invalid CSRF token";
    include __DIR__ . '/403.php';
    exit;
}

if (!empty($_POST['contact_middle_name'])) { // honeypot field
    http_response_code(403);
    $error_message = "Bot detected";
    include __DIR__ . '/403.php';
    exit;
}

if (time() - ($_SESSION['form_time'] ?? 0) < 3) {
    http_response_code(403);
    $error_message = "Too fast submission, possible bot";
    include __DIR__ . '/403.php';
    exit;
}


$ip = $_SERVER['REMOTE_ADDR'];
$now = time();

if (!isset($_SESSION['last_submit'])) {
    $_SESSION['last_submit'] = [];
}

$_SESSION['last_submit'][$ip] = $_SESSION['last_submit'][$ip] ?? 0;

if ($now - $_SESSION['last_submit'][$ip] < 10) { // 10 sec cooldown
    http_response_code(429);
    $error_message = "Please wait before sending another message";
    include __DIR__ . '/429.php';
    exit;
}

$_SESSION['last_submit'][$ip] = $now;


// check if same mail was sent in the last 10 minutes to prevent spam
if (!isset($_SESSION['last_message'])) {
    $_SESSION['last_message'] = [];
}

$_SESSION['last_message'][$ip] = $_SESSION['last_message'][$ip] ?? ['message' => '', 'time' => 0];

if ($message === $_SESSION['last_message'][$ip]['message'] && $now - $_SESSION['last_message'][$ip]['time'] < 600) { // 10 minutes cooldown for same message
    http_response_code(429);
    $error_message = "Please wait before sending the same message again";
    include __DIR__ . '/429.php';
    exit;
}


$name = trim($_POST['name']);
$message = trim($_POST['message']);

if (strlen($name) < 2 || strlen($name) > 50) {
    $error_message = "Le nom doit comporter entre 2 et 50 caractères.";
    include __DIR__ . '/contact.php';
    exit;
}

if (strlen($message) < 5 || strlen($message) > 2000) {
    $error_message = "Le message doit comporter entre 5 et 2000 caractères.";
    include __DIR__ . '/contact.php';
    exit;
}

$categorie = htmlspecialchars($_POST['categorie'] ?? 'General');
$name = htmlspecialchars($purifier->purify($name));
$message = htmlspecialchars($purifier->purify($message));

$mail_css_path = __DIR__ . '/assets/css/mail.css';
$mail_css = '';

if (is_readable($mail_css_path)) {
    $mail_css = file_get_contents($mail_css_path) ?: '';
}


// Category transcripted to be poetic and thematic
switch ($categorie) {
    case 'rejoindre':
        $categorie = "Vœu de rejoindre le saint culte";
        break;

    case 'beurre':
        $categorie = "Quête de l’auguste beurre sacré";
        break;

    case 'question':
        $categorie = "Interrogation adressée au Dieu du Beurre";
        break;

    case 'suggestion':
        $categorie = "Proclamation d’un conseil pour l’ordre sacré du Jambon-Beurre";
        break;

    case 'autre':
        $categorie = "Missive d’un humble mortel";
        break;

    default:
        $categorie = "Missive d’un humble mortel";
}


//better date format in french with the month in letters
$formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::LONG,
    IntlDateFormatter::NONE,
    'Europe/Paris',
    IntlDateFormatter::GREGORIAN,
    "d MMMM y 'à' HH:mm"
);

$date =  $formatter->format(new DateTime());



// $message = "
// Dieu du Beurre, une nouvelle missive a été reçue depuis le formulaire de contact du site de Beurreland !
// Catégorie : " . $categorie . "
// Nom : " . $name . "
// Message : " . $message . "
// Envoyé le : " . date('d/m/Y H:i:s') . "
// ";

// html email with the message in a nice format
$mail_body = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Nouvelle missive de " . $name . "</title>
    <style>" . $mail_css . "</style>
</head>
<body>
    <div class='mail-bg'>
        <table role='presentation' class='mail-wrapper' cellpadding='0' cellspacing='0'>
            <tr>
                <td>
                    <div class='mail-card'>
                        <p class='mail-kicker'>✦ Chronique des Messagers de Beurreland ✦</p>
                        <h1>Par la plume et l’encre sacrée, missive de " . $name . "</h1>
                        <p class='mail-description'>Ô grand Dieu du Beurre, sache qu’une nouvelle missive a été déposée en les registres du domaine de Beurreland, portée par vents et sortilèges depuis le formulaire de contact.</p>
                        <p class='mail-meta'><strong>Catégorie scellée :</strong> " . $categorie . "</p>
                        <div class='mail-message'>" . nl2br($message) . "</div>
                        <p class='mail-date'>Rédigé en ce jour du " . $date . ", consigné pour mémoire éternelle.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>";


$mail_subject_name = preg_replace('/[\r\n]+/', ' ', $_POST['name'] ?? 'inconnu');
$mail_subject_category = preg_replace('/[\r\n]+/', ' ', $_POST['categorie'] ?? 'General');

send_mail("Frescri@beurreland.cc", "Nouvelle missive de " . $mail_subject_name . " (" . $mail_subject_category . ")", $mail_body);
http_response_code(200);
$successes_message = "Votre message a été envoyé au Dieu du Beurre !";
include __DIR__ . '/contact.php';
