<?php http_response_code(403); ?>
<?php $current_file = __FILE__; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accès refusé</title>

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
    <div class="page">
        <div class="banner">
            <h1>403 - Beurre refusé</h1>
            <div class="subtitle">Désolé petit hackeur, seul le dieu du beurre peut accéder à cette page.</div>
        </div>

        <figure class="image">
            <img src="/src/img/frescri-hacker.png" alt="Beurre hacker" width="100">
            <figcaption>Beurre hacker</figcaption>
        </figure>

        <a href="/" class="big-center-link wave-auto">Retour à la page d'accueil</a>

        <?php include "footer.php"; ?>
    </div>



    <script src="/src/js/script.js"></script>
</body>

</html>