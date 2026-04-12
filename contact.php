<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Beurreland</title>

    <link rel="shortcut icon" href="./src/img/Butter_Pixel.png" type="image/x-icon">
    <link rel="stylesheet" href="./src/css/style.css">
</head>

<body>
    <div id="google_translate_element"></div>
    <div class="sidebar">
        <h1><a href="?jambon=beurre">
                <marquee>Beurreland</marquee>
            </a></h1>
        <div class="counter-container">
            <h3>Voici le compteur de visites :</h3>
            <div class="counter" id="counter">
                <div class="digit">b</div>
                <div class="digit">e</div>
                <div class="digit">u</div>
                <div class="digit">r</div>
                <div class="digit">r</div>
                <div class="digit">e</div>
            </div>
        </div>

        <div class="navlinks">
            <a href="/">Accueil</a>
            <a href="/404">Page 404</a>
            <a href="/403">Page 403</a>
            <a href="/500">Page 500</a>
            <a href="/contact">Contact</a>
            <a href="/github">Github</a>
            <a onclick="toggleEasterEgg()">Easter Egg</a>
        </div>
    </div>
    <div class="page">
        <div class="topbar">
            <marquee behavior="scroll" direction="left">Bienvenue sur Beurreland !</marquee>
        </div>
        <div class="banner">
            <h1>Contactez-nous</h1>
            <div class="subtitle">
                <p>Vous avez des questions, des suggestions ou simplement envie de discuter du beurre ? N'hésitez pas à nous contacter !</p>
            </div>
        </div>
        <div class="contact-form">
            <h2>Formulaire de contact</h2>
            <form action="mailto:frescri@beurreland.com" method="post" enctype="text/plain">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>



    <script src="./src/js/script.js"></script>
</body>

</html>