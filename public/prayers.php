<?php require_once __DIR__ . '/../src/php/init.php'; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prières | Beurreland</title>

    <link rel="shortcut icon" href="/assets/img/Butter_Pixel.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/style.css">

    <script src="/assets/js/snow.js"></script>
    <link rel="stylesheet" href="/assets/css/snow.css">

    <!-- Social Media Meta Tags -->
    <meta property="og:title" content="Prières | Beurreland">
    <meta property="og:description" content="Le petit recueil officiel du culte beurré">
    <meta property="og:image" content="/assets/img/Butter_Pixel.png">
    <meta property="og:url" content="https://beurreland.cc/prayers">
    <meta name="twitter:card" content="summary_large_image">

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
            <marquee behavior="scroll" direction="left">★ Récitez les prières sacrées du Jambon-Beurre ★</marquee>
        </div>

        <div class="banner">
            <h1>Prières</h1>
            <div class="subtitle">Le petit recueil officiel du culte beurré</div>
        </div>

        <div class="paragraph">
            <h2>Recueil des prières</h2>
            <p>Choisissez une prière, prenez une tartine et récitez avec ferveur.</p>

            <div class="prayer" id="NotreBeurre">
                <h3><a href="https://beurreland.cc/prayers#NotreBeurre">Notre Beurre</a></h3>
                <p>Notre Beurre, qui es salé,<br>
                que ta crèmes soit sanctifié,<br>
                que ton jambon soit vercuvien,<br>
                que ta vérité soit faite sur ma poêle comme sur mon pain.</p>

                <p>Donne-nous aujourd'hui notre jambon-beurre de ce jour.<br>
                Pardonne-nous notre régime,<br>
                comme nous pardonnons aussi à ceux qui ont maigris.</p>

                <p>Et ne nous laisse pas entrer dans le véganisme,<br>
                mais délivre-nous de la margarine.</p>

                <p>Car c'est à toi qu'appartiennent la cremière,<br>
                la puissance et la graisse,<br>
                pour les siècles des siècles.</p>

                <p>A-miam. </p>
            </div>
        </div>

        <?php include "../inc/footer.php"; ?>
    </div>

    <?php include "../inc/rsidebar.php"; ?>

    <script src="/assets/js/script.js"></script>
</body>

</html>