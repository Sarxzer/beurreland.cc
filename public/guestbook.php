<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once '../src/php/database.php';
require_once '../src/php/bbcode.php';

$messages = $pdo->query("SELECT * FROM guestbook WHERE `status` != 'deleted' ORDER BY id DESC")->fetchAll();
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
    <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
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
                <input type="text" id="name" name="name" required maxlength="50">

                <div class="bbcode-bar">
                    <button type="button" class="bbcode-btn" id="bb-bold">B</button>
                    <button type="button" class="bbcode-btn" id="bb-italic">I</button>
                    <button type="button" class="bbcode-btn" id="bb-underline">U</button>
                    <button type="button" class="bbcode-btn" id="bb-strikethrough">S</button>
                    <button type="button" class="bbcode-btn wave-auto" id="bb-wave">WAVE</button>
                    <button type="button" class="bbcode-btn" id="bb-url">URL</button>
                    <button type="button" class="bbcode-btn" id="bb-image"><img src="/assets/img/Butter_Pixel.png" alt="Image"></button>
                    <button type="button" class="bbcode-btn" id="bb-code">CODE</button>
                </div>
                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="text" name="website" style="display:none">

                <button id="submit-btn" type="submit">Envoyer au Dieu du Beurre</button>
            </form>
        </div>

        <div class="guestbook">
            <h2>Messages récents</h2>
            <button id="reload">Recharger</button>
            <div id="guestbook-messages" class="messages">
                <?php if (empty($messages)): ?>
                    <p>Aucun message pour le moment. Soyez le premier à laisser un message au Dieu du Beurre !</p>
                <?php endif; ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <button class="admin-only" onclick="deleteMessage(<?= $message['id'] ?>)">Supprimer id: <?= $message['id'] ?></button>
                        <span class="name"><?= htmlspecialchars($message['name']) ?></span>
                        <span class="date">(<?= date('\l\e d/m/Y à H:i', strtotime($message['created_at'])) ?>)</span>
                        <br>
                        <div class="content"><?= bbcode_to_html($message['message']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php include "../inc/footer.php"; ?>
    </div>

    <?php include "../inc/rsidebar.php"; ?>

    <script src="/assets/js/bbcode.js"></script>
    <script src="/assets/js/script.js"></script>


</body>

</html>