<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Pas de beurre</title>

    <link rel="stylesheet" href="/src/css/style.css">

    <script src="/src/js/snow.js"></script>
    <link rel="stylesheet" href="/src/css/snow.css">


</head>

<body>
    <div class="page">
        <div class="banner">
            <h1>404 - Pas de beurre</h1>
            <div class="subtitle">Désolé, la page que vous cherchez n'existe pas.</div>
        </div>

        <figure class="image">
            <img src="/src/img/Davide_Jambon-Beuere_2.gif" alt="Beurre triste" width="600">
            <figcaption>Beurre triste</figcaption>
        </figure>

        <a href="/" class="big-center-link wave-auto">Retour à la page d'accueil</a>

        <footer class="footer">
            <p><strong>© 2026 Dieu du Beurre</strong> - Tous droits réservés</p>
            <p><strong>© 2026 Sarxzer</strong> - Tous droits réservés</p>
            <p><strong>Contact officiel :</strong> <a href="mailto:frescri@beurreland.cc">frescri@beurreland.cc</a></p>
            <p><strong>Webmaster :</strong> <a href="mailto:sarxzer@sarxzer.xyz">sarxzer@sarxzer.xyz</a></p>
            <p class="small">
                Dernière mise à jour :
                <?= date("d/m/Y à H:i", filemtime(__FILE__)) ?> • Culte sacré en construction permanente • Hébergé avec
                amour,
                beurre et par un des serviteur du culte, <span class="tooltip">Sarxzer<span
                        class="tooltiptext">Webmaster du culte</span></span>.
            </p>
        </footer>
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