<?php
require_once 'src/php/database.php';

$messages = $pdo->query("SELECT * FROM guestbook ORDER BY id DESC")->fetchAll();
?>
<?php $current_file = __FILE__; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>

    <link rel="stylesheet" href="/src/css/style.css">

    <script src="/src/js/snow.js"></script>
    <link rel="stylesheet" href="/src/css/snow.css">

    <link rel="shortcut icon" href="/src/img/Butter_Pixel.png" type="image/x-icon">


    <!-- Google Translate -->
    <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
</head>

<body>
    <div id="google_translate_element"></div>

    <?php include "inc/sidebar.php"; ?>

    <div class="page">
        <div class="banner">
            <h1>Guestbook</h1>
            <div class="subtitle">Laissez un message au Dieu du Beurre !</div>
        </div>

        <div class="contact-form">
            <h2>Ajouter un message</h2>
            <form action="sign.php" method="post">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>

                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Envoyer au Dieu du Beurre</button>
            </form>
        </div>

        <div class="messages">
            <h2>Messages récents</h2>
            <?php if (empty($messages)): ?>
                <p>Aucun message pour le moment. Soyez le premier à laisser un message au Dieu du Beurre !</p>
            <?php endif; ?>
                <?php foreach ($messages as $m): ?>
                    <div class="message">
                        <b><?= htmlspecialchars($m['name']) ?></b>
                        (<?= date('\l\e d/m/Y à H:i', strtotime($m['created_at'])) ?>)
                        <br>
                        <?= nl2br(htmlspecialchars($m['message'])) ?>
                    </div>
                <?php endforeach; ?>
        </div>

        <?php include "inc/footer.php"; ?>
    </div>

    <?php include "inc/rsidebar.php"; ?>

    <script src="/src/js/script.js"></script>
    

</body>

</html>