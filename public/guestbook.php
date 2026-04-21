<?php
require_once '../src/php/database.php';

$messages = $pdo->query("SELECT * FROM guestbook ORDER BY id DESC")->fetchAll();

function bbcode_to_html($text) {

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

        // Image
        '/\[img\](https?:\/\/.*?)\[\/img\]/is' => '<a href="$1" target="_blank" rel="noopener"><img src="$1" alt="image" /></a>',

        // Code
        '/\[code\](.*?)\[\/code\]/is' => '<pre><code>$1</code></pre>',
    ];

    foreach ($bbcodes as $pattern => $replace) {
        $text = preg_replace($pattern, $replace, $text);
    }

    return nl2br($text);
}
?>
<?php $current_file = __FILE__; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>

    <link rel="stylesheet" href="/assets/css/style.css">

    <script src="/assets/js/snow.js"></script>
    <link rel="stylesheet" href="/assets/css/snow.css">

    <link rel="shortcut icon" href="/assets/img/Butter_Pixel.png" type="image/x-icon">


    <!-- Google Translate -->
    <!-- <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script> -->
</head>

<body>
    <div id="google_translate_element"></div>

    <?php include "../inc/sidebar.php"; ?>

    <div class="page">
        <div class="topbar">
            <marquee behavior="scroll" direction="left">★ Laissez un message au Dieu du Beurre dans notre livre d'or sacré ★</marquee>
        </div>
        <div class="banner">
            <h1>Guestbook</h1>
            <div class="subtitle">Laissez un message au Dieu du Beurre !</div>
        </div>

        <div class="guestbook-form">
            <h2>Ajouter un message</h2>
            <form action="sign.php" method="post">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>

                <div class="bbcode-bar">
                    <button type="button" class="bbcode-btn" id="bb-bold">B</button>
                    <button type="button" class="bbcode-btn" id="bb-italic">I</button>
                    <button type="button" class="bbcode-btn" id="bb-underline">U</button>
                    <button type="button" class="bbcode-btn" id="bb-strikethrough">S</button>
                    <button type="button" class="bbcode-btn" id="bb-url">URL</button>
                    <button type="button" class="bbcode-btn" id="bb-image"><img src="/assets/img/Butter_Pixel.png" alt="Image"></button>
                    <button type="button" class="bbcode-btn" id="bb-code">CODE</button>
                </div>
                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button id="submit-btn" type="submit">Envoyer au Dieu du Beurre</button>
            </form>
        </div>

        <div class="messages">
            <h2>Messages récents</h2>
            <?php if (empty($messages)): ?>
                <p>Aucun message pour le moment. Soyez le premier à laisser un message au Dieu du Beurre !</p>
            <?php endif; ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <span class="name"><?= htmlspecialchars($message['name']) ?></span>
                        <span class="date">(<?= date('\l\e d/m/Y à H:i', strtotime($message['created_at'])) ?>)</span>
                        <br>
                        <div class="content"><?= bbcode_to_html($message['message']) ?></div>
                    </div>
                <?php endforeach; ?>
        </div>

        <?php include "../inc/footer.php"; ?>
    </div>

    <?php include "../inc/rsidebar.php"; ?>

    <script src="/assets/js/bbcode.js"></script>
    <script src="/assets/js/script.js"></script>
    

</body>

</html>