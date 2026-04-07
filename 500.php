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


</head>

<body>
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

        <?php include "footer.php"; ?>
    </div>



    <script>
        document.querySelectorAll(".wave-auto").forEach((link) => {
            const text = link.textContent;
            link.textContent = "";
            [...text].forEach((char, i) => {
                const span = document.createElement("span");
                span.classList.add("wave");
                span.textContent = char === " " ? "\u00A0" : char;
                span.style.animationDelay = `${i * 0.05}s`;
                link.appendChild(span);
            });
        });

        const params = new URLSearchParams(window.location.search);

        if (params.get("beurre") === "beurre") {
            snow(50, 50, 75, 15, 5);
        }
    </script>
</body>

</html>