<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$_SESSION['form_time'] = time(); // Store the time when the form is generated to prevent spam

?>
<?php $current_file = __FILE__; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Beurreland</title>

    <link rel="shortcut icon" href="/assets/img/Butter_Pixel.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/style.css">

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
        <?php 
        if (isset($successes_message)) {
            echo '<div class="success-message">' . htmlspecialchars($successes_message) . '</div>';
        }
        if (isset($error_message)) {
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>
        <div class="contact-form">
            <h2>Formulaire de contact</h2>
            <div class="subtitle">
                <p>Remplissez le formulaire ci-dessous pour poser vos questions au Dieu du Beurre !</p>
            </div>
            <form action="mail.php" method="post" id="contact-form">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="categorie">Catégorie :</label>
                <select id="categorie" name="categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="rejoindre">Souhaite rejoindre le culte</option>
                    <option value="beurre">Demande de beurre</option>
                    <option value="question">Question</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="autre">Autre</option>
                </select>

                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="text" name="contact_middle_name" style="display:none">


                <button type="submit">Envoyer au Dieu du Beurre</button>
            </form>
        </div>

        <?php include '../inc/footer.php'; ?>
    </div>

    <?php include "../inc/rsidebar.php"; ?>



    <script src="/assets/js/script.js"></script>
</body>

</html>