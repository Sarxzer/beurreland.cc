<?php http_response_code(500); ?>
<?php $current_file = __FILE__; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur interne du serveur</title>

    <link rel="stylesheet" href="/src/css/style.css">

    <script src="/src/js/snow.js"></script>
    <link rel="stylesheet" href="/src/css/snow.css">


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
            <h1>500 - Erreur interne du serveur</h1>
            <div class="subtitle">Désolé, le Dieu du Beurre s'est transformé en Fresgozila et a détruit le serveur.</div>
        </div>

        <figure class="image">
            <img src="/src/img/Fresgozila.png" alt="Fresgozila" width="100">
            <figcaption>Fresgozila ravageant le serveur</figcaption>
        </figure>

        <a href="/" class="big-center-link wave-auto">Retour à la page d'accueil</a>

        <?php include "inc/footer.php"; ?>
    </div>

    <?php include "inc/rsidebar.php"; ?>

    <script src="/src/js/script.js"></script>
</body>

</html>