<?php require_once __DIR__ . '/../src/php/init.php'; ?>
<?php http_response_code(404); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Pas de beurre</title>

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
        <div class="banner">
            <h1>404 - Pas de <span class="tooltip">beuere <p class="tooltiptext">Désolé, le dieu du beurre est dyslexique</p></span></h1>
            <div class="subtitle">Désolé, mais le dieu du beurre n'a pas crée cette page.</div>
        </div>

        <figure class="image">
            <img src="/assets/img/davide-jambon-beuere.gif" alt="Beurre pas de bol" width="600">
            <figcaption>Beurre pas de bol</figcaption>
        </figure>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <a href="/" class="big-center-link wave-auto">Retour à la page d'accueil</a>

        <?php include "../inc/footer.php"; ?>
    </div>

    <?php include "../inc/rsidebar.php"; ?>

    <script src="/assets/js/script.js"></script>
</body>

</html>