<?php http_response_code(429); ?>
<?php require_once __DIR__ . '/../src/php/init.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Trop de requêtes</title>

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
            <h1>429 - Trop de requêtes</h1>
            <div class="subtitle">Désolé, le Dieu du Beurre n'est pas un dieu de la rapidité.</div>
        </div>

        <figure class="image">
            <img src="/assets/img/davide-jambon-beuere.gif" alt="Davide jambon beurre Temporairement" width="100">
            <figcaption>Désolé, pas de meme pour ce cas</figcaption>
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