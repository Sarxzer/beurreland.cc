<?php $current_file = __FILE__; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Github Info Page</title>

    <link rel="stylesheet" href="/src/css/style.css">

    <script src="./src/js/snow.js"></script>
    <link rel="stylesheet" href="./src/css/snow.css">

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
            <h1>Github Info</h1>
            <div class="subtitle"><a href="https://github.com/Sarxzer/Beurreland.cc" target="_blank">Informations sur le projet Beurreland sur Github</a></div>
        </div>

        <div class="paragraph">
            <div class="info">
                <p>Ce projet est hébergé sur Github, où vous pouvez trouver le code source, les commits récents et contribuer si vous le souhaitez !</p>
                <br>
                <div id="commit-count"><strong>Nombre de commits : </strong>...</div>
            </div>
            <div id="latest-commit">Chargement...</div>
            <br>
            <br>
            <div id="commits"></div>
        </div>

        <?php include 'inc/footer.php'; ?>
    </div>

    <?php include "inc/rsidebar.php"; ?>

    <script src="/src/js/script.js"></script>
    <script src="/src/js/github.js"></script>
</body>

</html>