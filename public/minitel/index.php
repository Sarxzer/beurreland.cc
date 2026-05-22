<?php
require_once __DIR__ . '/../../src/php/mailer.php';

use MiniPavi\MiniPaviCli;

MiniPaviCli::start();

if (MiniPaviCli::$fctn === 'FIN' || MiniPaviCli::$fctn === 'FCTN?') {
    exit;
}

function ascii_text(string $text): string
{
    $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($normalized === false) {
        $normalized = $text;
    }
    $normalized = preg_replace('/[^\x00-\x7F]/', '', $normalized);
    return $normalized;
}

function split_sentences(string $paragraph): array
{
    $paragraph = trim($paragraph);
    if ($paragraph === '') {
        return [];
    }
    $sentences = preg_split('/(?<=[.!?])\s+/', $paragraph);
    if (!is_array($sentences) || empty($sentences)) {
        return [$paragraph];
    }
    $sentences = array_map('trim', $sentences);
    return array_values(array_filter($sentences, 'strlen'));
}

function wrap_sentence_lines(string $sentence, int $width): array
{
    $sentence = trim($sentence);
    if ($sentence === '') {
        return [];
    }
    $wrapped = wordwrap($sentence, $width, "\n", true);
    return explode("\n", $wrapped);
}

function get_lore_pages(int $width, int $linesPerPage): array
{
    $path = BASE_PATH . 'lore.txt';
    $text = file_exists($path) ? file_get_contents($path) : '';
    $text = ascii_text($text);
    if ($text === '') {
        $text = 'Lore indisponible pour le moment.';
    }

    $paragraphs = preg_split("/\r?\n\s*\r?\n/", $text);
    $pages = [];
    $current = [];

    $pushPage = function () use (&$pages, &$current): void {
        if (!empty($current)) {
            $pages[] = $current;
            $current = [];
        }
    };

    $addBlock = function (array $block) use (&$current, &$pages, $linesPerPage, $pushPage): void {
        if (empty($block)) {
            return;
        }
        $isBlank = count($block) === 1 && $block[0] === '';
        if ($isBlank && empty($current)) {
            return;
        }
        if (count($current) + count($block) > $linesPerPage) {
            $pushPage();
            if ($isBlank) {
                return;
            }
        }
        if (count($block) > $linesPerPage) {
            $offset = 0;
            while ($offset < count($block)) {
                $chunk = array_slice($block, $offset, $linesPerPage);
                if (!empty($current)) {
                    $pushPage();
                }
                $pages[] = $chunk;
                $offset += $linesPerPage;
            }
            return;
        }
        $current = array_merge($current, $block);
    };

    foreach ($paragraphs as $paragraph) {
        $paragraph = trim(preg_replace('/\s+/', ' ', $paragraph));
        if ($paragraph === '') {
            continue;
        }
        $sentences = split_sentences($paragraph);
        foreach ($sentences as $sentence) {
            $lines = wrap_sentence_lines($sentence, $width);
            $addBlock($lines);
        }
        $addBlock(['']);
    }

    if (!empty($current)) {
        if (count($current) === 1 && $current[0] === '') {
            $current = [];
        }
    }
    if (!empty($current)) {
        $pages[] = $current;
    }

    if (empty($pages)) {
        $pages[] = [''];
    }

    $lastIndex = count($pages) - 1;
    if (isset($pages[$lastIndex]) && !empty($pages[$lastIndex])) {
        while (!empty($pages[$lastIndex]) && $pages[$lastIndex][count($pages[$lastIndex]) - 1] === '') {
            array_pop($pages[$lastIndex]);
        }
        if (empty($pages[$lastIndex])) {
            array_pop($pages);
        }
    }

    if (empty($pages)) {
        $pages[] = [''];
    }

    return $pages;
}

function render_header(string $title): string
{
    $vdt = MiniPaviCli::clearScreen();
    $vdt .= MiniPaviCli::setPos(1, 1) . VDT_BGBLUE . MiniPaviCli::repeatChar(' ', 40);
    $vdt .= MiniPaviCli::writeCentered(1, $title, VDT_BGBLUE . VDT_TXTWHITE);
    $vdt .= VDT_FDNORM;
    return $vdt;
}

function button_label(string $label): string
{
    return VDT_BGYELLOW . VDT_TXTBLACK . ' ' . $label . ' ' . VDT_BGBLACK . VDT_TXTWHITE;
}

$context = MiniPaviCli::$context ? unserialize(MiniPaviCli::$context) : ['step' => 'home'];
$step = $context['step'] ?? 'home';

$vdt = '';
$cmd = null;
$directCall = false;

while (true) {
    switch ($step) {
        case 'home':
            $vdt = render_header('BEURRELAND');
            $vdt .= MiniPaviCli::writeCentered(3, 'MINITEL DE BEURRELAND', VDT_TXTWHITE);
            $vdt .= MiniPaviCli::setPos(2, 6) . '1) Contact';
            $vdt .= MiniPaviCli::setPos(2, 7) . '2) Lore';
            $vdt .= MiniPaviCli::setPos(2, 8) . '3) Liens';
            $vdt .= MiniPaviCli::setPos(2, 10) . 'Choix:';
            $vdt .= MiniPaviCli::setPos(2, 20) . 'Le Dieu du Beurre vous salue.';
            $vdt .= file_get_contents(__DIR__ . '/../assets/beurre.vdt');
            if (!empty($context['flash'])) {
                $vdt .= MiniPaviCli::writeLine0($context['flash']);
                unset($context['flash']);
            }
            $cmd = MiniPaviCli::createInputTxtCmd(9, 10, 1, MSK_ENVOI, true, '.');
            $context['step'] = 'menu_choice';
            break 2;

        case 'menu_choice':
            $choice = trim(MiniPaviCli::$content[0] ?? '');
            if ($choice === '1') {
                $step = 'contact_name';
                $context['step'] = $step;
                continue;
            }
            if ($choice === '2') {
                $context['lore_page'] = 0;
                $step = 'lore_nav';
                $context['step'] = $step;
                continue;
            }
            if ($choice === '3') {
                $step = 'links';
                $context['step'] = $step;
                continue;
            }
            $context['flash'] = 'Choix invalide.';
            $step = 'home';
            $context['step'] = $step;
            continue;

        case 'contact_name':
            $vdt = render_header('CONTACT');
            $vdt .= MiniPaviCli::setPos(2, 4) . 'Nom:';
            $vdt .= MiniPaviCli::setPos(2, 6) . 'Entrez votre nom puis' . button_label('Envoi') . '.';
            $vdt .= MiniPaviCli::setPos(2, 7) . 'Ou ' . button_label('Sommaire') . ' pour menu.';   
            if (!empty($context['flash'])) {
                $vdt .= MiniPaviCli::writeLine0($context['flash']);
                unset($context['flash']);
            }
            $cmd = MiniPaviCli::createInputTxtCmd(2, 5, 30, MSK_ENVOI | MSK_SOMMAIRE, true, '.');
            $context['step'] = 'contact_name_submit';
            break 2;

        case 'contact_name_submit':
            if (MiniPaviCli::$fctn === 'SOMMAIRE') {
                $step = 'home';
                $context['step'] = $step;
                continue;
            }
            $name = sanitize_input(trim(MiniPaviCli::$content[0] ?? ''));
            if (strlen($name) < 2 || strlen($name) > 50) {
                $context['flash'] = 'Nom trop court.';
                $step = 'contact_name';
                $context['step'] = $step;
                continue;
            }
            $context['contact_name'] = $name;
            $step = 'contact_message';
            $context['step'] = $step;
            continue;

        case 'contact_message':
            $vdt = render_header('CONTACT');
            $vdt .= MiniPaviCli::setPos(2, 4) . 'Message:';
            $vdt .= MiniPaviCli::setPos(2, 12) . button_label('Envoi') . ' pour envoyer.';
            if (!empty($context['flash'])) {
                $vdt .= MiniPaviCli::writeLine0($context['flash']);
                unset($context['flash']);
            }
            $cmd = MiniPaviCli::createInputMsgCmd(2, 5, 37, 6, MSK_ENVOI | MSK_SOMMAIRE, true, '.');
            $context['step'] = 'contact_message_submit';
            break 2;

        case 'contact_message_submit':
            if (MiniPaviCli::$fctn === 'SOMMAIRE') {
                $step = 'home';
                $context['step'] = $step;
                continue;
            }
            $message = sanitize_input(trim(implode("\n", MiniPaviCli::$content ?? [])));
            if (strlen($message) < 5 || strlen($message) > 2000) {
                $context['flash'] = 'Message invalide.';
                $step = 'contact_message';
                $context['step'] = $step;
                continue;
            }

            $ip = getUserIP();
            $now = time();
            if (!isset($_SESSION['minitel_last_submit'])) {
                $_SESSION['minitel_last_submit'] = [];
            }
            $_SESSION['minitel_last_submit'][$ip] = $_SESSION['minitel_last_submit'][$ip] ?? 0;
            if ($now - $_SESSION['minitel_last_submit'][$ip] < 10) {
                $context['flash'] = 'Attendez un peu avant un nouvel envoi.';
                $step = 'contact_message';
                $context['step'] = $step;
                continue;
            }
            $_SESSION['minitel_last_submit'][$ip] = $now;

            $name = $context['contact_name'] ?? 'Minitel';
            $safeName = preg_replace('/[\r\n]+/', ' ', $name);

            $html = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Minitel — Beurreland</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=VT323&family=Share+Tech+Mono&display=swap");
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0a0a0f; font-family: "Share Tech Mono", monospace; padding: 32px 16px; color: #ccddff; }
        .card { max-width: 520px; margin: 0 auto; }
        .kicker { font-size: 12px; color: #ffdd00; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 14px; }
        .from { font-family: "VT323", monospace; font-size: 28px; color: #fff; margin-bottom: 12px; }
        .description { font-size: 13px; color: #8899cc; line-height: 1.7; margin-bottom: 16px; }
        .message { font-size: 13px; line-height: 1.75; border-left: 2px solid #0044cc; padding-left: 14px; white-space: pre-wrap; word-break: break-word; margin-bottom: 20px; }
        .date { font-size: 11px; color: #445588; letter-spacing: 1px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <p class="kicker">✦ Chronique des Messagers de Beurreland ✦</p>
        <p class="from">Par la plume et l\'encre sacrée, missive de ' . $safeName . '</p>
        <p class="description">Ô grand Dieu du Beurre, sache qu\'une nouvelle missive a été déposée en les registres du domaine de Beurreland, portée par vents et sortilèges depuis le terminal Minitel.</p>
        <div class="message">' . $message . '</div>
        <p class="date">Rédigé en ce jour du ' . date('d/m/Y') . '  , consigné pour mémoire éternelle.</p>
    </div>
</body>
</html>';
            $sent = send_mail('Frescri@beurreland.cc', 'Nouvelle missive de ' . $safeName, $html);

            $context['contact_status'] = $sent ? 'Message envoye.' : 'Echec envoi.';
            $step = 'contact_done';
            $context['step'] = $step;
            continue;

        case 'contact_done':
            $vdt = render_header('CONTACT');
            $status = $context['contact_status'] ?? 'Termine.';
            $vdt .= MiniPaviCli::writeCentered(6, $status);
            $vdt .= MiniPaviCli::setPos(1, 10) . VDT_CLRLN;
            $vdt .= MiniPaviCli::setPos(2, 10) . button_label('Envoi') . ' ou ' . button_label('Sommaire') . ' pour menu.';
            $cmd = MiniPaviCli::createInputTxtCmd(2, 11, 1, MSK_ENVOI | MSK_SOMMAIRE, true, '.');
            unset($context['contact_status'], $context['contact_name']);
            $context['step'] = 'home';
            break 2;

        case 'lore_nav':
            if (MiniPaviCli::$fctn === 'SOMMAIRE') {
                $step = 'home';
                $context['step'] = $step;
                continue;
            }

            $pages = get_lore_pages(38, 18);
            $totalPages = count($pages);
            $page = (int) ($context['lore_page'] ?? 0);

            if (MiniPaviCli::$fctn === 'SUITE') {
                $page++;
            }
            if (MiniPaviCli::$fctn === 'RETOUR') {
                $page--;
            }

            if ($page < 0) {
                $page = 0;
            }
            if ($page > $totalPages - 1) {
                $page = $totalPages - 1;
            }
            $context['lore_page'] = $page;

            $vdt = render_header('LORE');
            $lines = $pages[$page] ?? [];
            $lineNo = 4;
            foreach ($lines as $line) {
                $vdt .= MiniPaviCli::setPos(1, $lineNo) . VDT_TXTWHITE . $line;
                $lineNo++;
            }
            $vdt .= MiniPaviCli::setPos(2, 22) . VDT_TXTYELLOW . 'Page ' . ($page + 1) . '/' . $totalPages;
            $vdt .= MiniPaviCli::setPos(2, 23)
                . button_label('Suite') . ' '
                . button_label('Retour') . ' '
                . button_label('Sommaire');
            $vdt .= MiniPaviCli::setPos(2, 24) . VDT_TXTWHITE . 'Action:';
            $cmd = MiniPaviCli::createInputTxtCmd(10, 24, 1, MSK_SUITE | MSK_RETOUR | MSK_SOMMAIRE, true, '.');
            $context['step'] = 'lore_nav';
            break 2;

        case 'links':
            $vdt = render_header('LIENS');
            $vdt .= MiniPaviCli::setPos(2, 5) . 'Site : https://beurreland.cc';
            $vdt .= MiniPaviCli::setPos(2, 6) . 'Github: github.com/Sarxzer/beurreland.cc';
            $vdt .= MiniPaviCli::setPos(2, 8) . 'Contact: menu -> Contact';
            $vdt .= MiniPaviCli::setPos(2, 12) . button_label('Sommaire') . ' pour menu.';
            $cmd = MiniPaviCli::createInputTxtCmd(2, 13, 1, MSK_SOMMAIRE | MSK_ENVOI, true, '.');
            $context['step'] = 'home';
            break 2;

        default:
            $step = 'home';
            $context['step'] = $step;
            continue;
    }
}

$nextUrl = (empty($_SERVER['HTTPS']) ? 'http' : 'https')
    . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

MiniPaviCli::send($vdt, $nextUrl, serialize($context), true, $cmd, $directCall);
