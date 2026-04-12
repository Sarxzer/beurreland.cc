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
    
    <?php include "inc/sidebar.php"; ?>

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
        <div class="wip">
            <h2>Site en construction, revenez plus tard pour découvrir le culte beurré du Jambon-Beurre !</h2>
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

    <?php include "inc/rsidebar.php"; ?>



    <script src="./src/js/script.js"></script>
</body>

</html>