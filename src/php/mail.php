<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$phpmailer = new PHPMailer\PHPMailer\PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = $_ENV['MAIL_HOST'];
$phpmailer->SMTPAuth = true;
$phpmailer->Username = $_ENV['MAIL_USER'];
$phpmailer->Password = $_ENV['MAIL_PASS'];
$phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
$phpmailer->Port = $_ENV['MAIL_PORT'];

// headers to prevent encoding issues with accents and special characters
$phpmailer->CharSet = 'UTF-8';
$phpmailer->Encoding = 'base64';

/**
 * Test the mail configuration by sending a test email to the configured test recipient
 * @return bool
 */
function test_mail() {
    global $phpmailer;

    $phpmailer->clearAllRecipients();
    $phpmailer->clearReplyTos();
    $phpmailer->clearAttachments();

    $phpmailer->setFrom($_ENV['MAIL_USER'], 'Beurreland');
    $phpmailer->addAddress($_ENV['MAIL_TEST_RECIPIENT']);
    $phpmailer->Subject = 'Test de mail';
    $phpmailer->isHTML(false);
    $phpmailer->Body = 'Ceci est un test de mail envoyé depuis le site de Beurreland.';
    return $phpmailer->send();
}

/**
 * Send an email using PHPMailer with the configured SMTP settings
 * @param string $to recipient email address
 * @param string $subject email subject
 * @param string $body email body, can be HTML or plain text depending on the $is_html parameter
 * @param bool $is_html
 * @return bool
 */
function send_mail(string $to, string $subject, string $body, bool $is_html = true) {
    global $phpmailer;

    $phpmailer->clearAllRecipients();
    $phpmailer->clearReplyTos();
    $phpmailer->clearAttachments();

    $phpmailer->setFrom($_ENV['MAIL_USER'], 'Beurreland');
    $phpmailer->addAddress($to);
    $phpmailer->Subject = $subject;
    if ($is_html) {
        $phpmailer->isHTML(true);
        $phpmailer->Body = $body;
    } else {
        $phpmailer->isHTML(false);
        $phpmailer->Body = strip_tags($body);
    }
    
    return $phpmailer->send();
}
