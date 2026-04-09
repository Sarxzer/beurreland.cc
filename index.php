<?php $current_file = __FILE__; ?>
<?php opcache_reset(); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beurreland — La religion sacrée du beurre</title>
    <link rel="shortcut icon" href="./src/img/Butter_Pixel.png" type="image/x-icon">

    <link rel="stylesheet" href="./src/css/style.css">

    <script src="./src/js/snow.js"></script>
    <link rel="stylesheet" href="./src/css/snow.css">

    <meta name="description"
        content="Bienvenue sur Beurreland, le royaume du beurre. Rejoignez notre culte sacré dédié au Jambon-Beurre, découvrez nos histoires beurrées et partagez votre amour pour le beurre avec nous !">

    <meta name="keywords" content="beurre, religion du beurre, beurreland, humour, site rétro, jambon beurre">

    <meta name="author" content="Sarxzer">

    <meta property="og:title" content="Beurreland — La religion du beurre">
    <meta property="og:description" content="Le site le plus beurré d'internet.">
    <meta property="og:type" content="website">

</head>

<body>
    <div class="sidebar">
        <h1><a href="?beurre=beurre">
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
            <a href="/404.php">Page 404</a>
            <a href="/403.php">Page 403</a>
            <a href="/500.php">Page 500</a>
            <a href="/contact.php">Contact</a>
            <a href="/scrogneugneu">Scrognéugneu</a>
            <a onclick="toggleEasterEgg()">Easter Egg</a>
        </div>
    </div>

    <div class="page">
        <div class="topbar">
            <marquee behavior="scroll" direction="left">★ Bienvenue dans le royaume officiel du Jambon-Beurre ★</marquee>
        </div>

        <div class="banner">
            <div class="small blink">NOUVEAU !!! CULTE OFFICIEL EN CONSTRUCTION !!! GLOIRE AU BEURRE !!!</div>
            <h1 class="wave-auto">Beurreland</h1>
            <div class="subtitle">Le culte sacré et légèrement salé du Jambon-Beurre</div>
        </div>

        <div class="wip">
            <h2>Site en construction, revenez plus tard pour découvrir le culte beurré du Jambon-Beurre !</h2>
        </div>
        <span class="tooltip">
            Je vais vous raconter la folle histoire du jambon-beurre. Il était une fois un jambon vercuvin qui se promenait tranquillement sur Pandora, explorant les forêts luxuriantes et les montagnes flottantes de cette planète étrange et fascinante. Ce jambon vercuvin n’était pas comme les autres ; il avait une certaine allure, une présence qui attirait le regard de quiconque croisait son chemin, qu’il s’agisse des créatures locales ou des rares voyageurs interstellaires qui passaient par là. Chaque pas du jambon résonnait doucement sur le sol recouvert de plantes phosphorescentes, et il semblait comme s’il se déplaçait avec un objectif mystérieux que personne ne pouvait comprendre.
            <br><br>
            Un jour, alors qu’il avançait avec son pas tranquille, le jambon vercuvin rencontra Frescri, un chasseur de l’arche occupé à farmer des armes rares et précieuses dans les coins les plus reculés de Pandora. Frescri, concentré sur sa mission mais toujours attentif aux bizarreries de l’univers, aperçut soudain ce jambon vercuvin. Il ne put s’empêcher de sourire en voyant cette créature si inhabituelle et trouva sa présence particulièrement amusante. Rapidement, Frescri commença à l’intégrer dans tous ses délires, imaginant des histoires absurdes et des situations improbables dans lesquelles le jambon vercuvin jouait un rôle central, parfois héros, parfois simple compagnon silencieux dans ses aventures.
            <br><br>
            Puis, quelques jours plus tard, Frescri découvrit le terme « beurre » dans un carnet ancien qu’il avait trouvé, rempli de notes, de croquis et de mots oubliés. Le mot l’intrigua, et il prit le temps d’en savourer la sonorité et la texture imaginaire, trouvant là encore une source de plaisir simple mais intense. Le beurre représentait pour lui quelque chose de doux et universel, quelque chose qui pouvait se combiner avec presque tout pour créer un effet inattendu et plaisant.
            <br><br>
            C’est alors qu’une idée germa dans son esprit. Pourquoi ne pas combiner ces deux choses qu’il aimait autant, le jambon vercuvin et le beurre, pour créer quelque chose de totalement nouveau ? Il réfléchit longuement, imaginant toutes les manières possibles de les fusionner, de leur donner une forme, un sens, et surtout une raison d’exister ensemble. Après plusieurs essais, discussions et moments de pure inspiration, il décida finalement de les unir dans une seule entité, donnant naissance à ce que l’on appellerait désormais le jambon-beurre.
            <br><br>
            Ainsi naquit ce concept étrange, inattendu et pourtant parfait dans sa simplicité. Le jambon vercuvin et le beurre, chacun remarquable à sa manière, furent réunis par l’imagination et la curiosité de Frescri, devenant le symbole d’un délire partagé et d’une rencontre fortuite qui prit une importance disproportionnée dans la vie de ceux qui y participaient. Voilà comment, à travers la coïncidence et la créativité d’un chasseur de l’arche et d’un simple jambon, cette étrange association vit le jour et s’imposa comme un petit miracle de l’absurde, une histoire que l’on raconte encore aujourd’hui.
            <span class="tooltiptext">Ne juger pas, chat-gpt l'a écrit, j'avais la flemme <br> ~Webmaster</span>
        </span>
        <?php include "footer.php"; ?>
    </div>



    <script>
        function updateCounter(counterValue, digitElements) {
            const str = counterValue.toString().padStart(6, '0');
            str.split('').forEach((num, i) => {
                console.log(`Updating digit ${i} to ${num}`);
                digitElements[i].textContent = num;
            });
        }


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
            snow(50, 25, 75, 15, 5);
        }

        const digits = document.querySelectorAll(".digit");


        fetch("/counter.php?increment=1")
            .then(r => r.text())
            .then(n => {
                if (isNaN(n)) {
                    console.error("Invalid counter value received:", n);
                    return;
                } else {
                    let visits = parseInt(n);
                    updateCounter(visits, digits);
                    console.log(`Counter updated: ${visits} visits`);
                }
            });
    </script>
    <script>
        const secret = [
            "3", "9", "5", "2", "4", "8"
        ];

        let buffer = [];

        document.addEventListener("keydown", (e) => {
            buffer.push(e.key);

            // Keep only last N keys
            if (buffer.length > secret.length) {
                buffer.shift();
            }

            // Compare arrays
            if (JSON.stringify(buffer).toLowerCase() === JSON.stringify(secret).toLowerCase()) {
                toggleEasterEgg();
                buffer = []; // reset so it can be triggered again later
            }
        });

        function toggleEasterEgg() {
            const isActive = document.documentElement.style.getPropertyValue('--beurre-yellow') === '#842593';
            if (isActive) {
                removeEasterEgg();
            } else {
                activateEasterEgg();
            }
        }

        function activateEasterEgg() {
            //chqnge all root colors variables to new
            // actual ones :
            // :root {
            //     --beurre-yellow: #ffffcc;
            //     --beurre-light: #fff8a6;
            //     --beurre-light-secondary: #fbeda0;
            //     --beurre-dark: #c58d2b;
            //     --beurre-text: #2f1d00;
            //     --beurre-text-light: #b14c00;
            //     --beurre-text-secondary: #6d3900;
            //     --beurre-shadow: #f6d57b;
            //     --beurre-topbar-start: #ffef72;
            //     --beurre-topbar-end: #f6c745;
            // }

            //add 'animatiom: spinY 5s linear infinite;' to body, wait a forth the duration of the animation (2.5s) and then change all the clors and the rest of the easter egg, then remove the animation so it doesn't keep spinning forever
            document.body.style.animation = "spinY 5s linear infinite";

            setTimeout(() => {
                document.documentElement.style.setProperty('--beurre-yellow', '#842593'); //new one is purple
                document.documentElement.style.setProperty('--beurre-light', '#d9a1e8'); //new one is light purple
                document.documentElement.style.setProperty('--beurre-light-secondary', '#f0c1f7'); //new one is even lighter purple
                document.documentElement.style.setProperty('--beurre-mid', '#c080c0'); //new one is pinkish purple
                document.documentElement.style.setProperty('--beurre-dark', '#4b1460'); //new one is dark purple
                document.documentElement.style.setProperty('--beurre-text', '#ffffff'); //new one is white
                document.documentElement.style.setProperty('--beurre-text-light', '#e0e0e0'); //new one is light gray
                document.documentElement.style.setProperty('--beurre-text-secondary', '#a0a0a0'); //new one is gray
                document.documentElement.style.setProperty('--beurre-shadow', '#c080c0'); //new one is pinkish purple
                document.documentElement.style.setProperty('--beurre-topbar-start', '#d9a1e8'); //new one is light purple
                document.documentElement.style.setProperty('--beurre-topbar-end', '#842593'); //new one is purple

                document.querySelectorAll("footer").forEach(footer => {
                    footer.style.transform = "rotate(180deg)";
                });

                document.querySelectorAll(".snowflake-image").forEach(img => {
                    img.src = "/src/img/PurpleGuy.webp";
                    img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
                    img.style.height = "auto";
                });

                // change the title and favicon
                document.title = "Beurreland — Le royaume du Purple Guy";
                const favicon = document.querySelector("link[rel='shortcut icon']");
                if (favicon) {
                    favicon.href = "/src/img/Exotic_Butters.webp";
                }
            }, 3750); // wait a forth of the duration of the animation (2.5s) before changing colors and the rest of the easter egg

            setTimeout(() => {
                document.body.style.animation = "";
            }, 5000); // remove the animation after it has completed one full rotation (5s)

        }

        function removeEasterEgg() {

            document.body.style.animation = "spinY 5s linear infinite";
            setTimeout(() => {

                // reset colors to original
                document.documentElement.style.setProperty('--beurre-yellow', '#ffffcc');
                document.documentElement.style.setProperty('--beurre-light', '#fff8a6');
                document.documentElement.style.setProperty('--beurre-light-secondary', '#fbeda0');
                document.documentElement.style.setProperty('--beurre-mid', '#f5cf5f');
                document.documentElement.style.setProperty('--beurre-dark', '#c58d2b');
                document.documentElement.style.setProperty('--beurre-text', '#2f1d00');
                document.documentElement.style.setProperty('--beurre-text-light', '#b14c00');
                document.documentElement.style.setProperty('--beurre-text-secondary', '#6d3900');
                document.documentElement.style.setProperty('--beurre-shadow', '#f6d57b');
                document.documentElement.style.setProperty('--beurre-topbar-start', '#ffef72');
                document.documentElement.style.setProperty('--beurre-topbar-end', '#f6c745');

                document.querySelectorAll("footer").forEach(footer => {
                    footer.style.transform = "rotate(0deg)";
                });

                document.querySelectorAll(".snowflake-image").forEach(img => {
                    img.src = "/src/img/jambon-beurre.png";
                    img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
                    img.style.height = "auto";
                });

                // change the title and favicon back to original
                document.title = "Beurreland — La religion sacrée du beurre";
                const favicon = document.querySelector("link[rel='shortcut icon']");
                if (favicon) {
                    favicon.href = "/src/img/Butter_Pixel.png";
                }
            }, 1250); // wait a forth of the duration of the animation (2.5s) before changing colors and the rest of the easter egg

            setTimeout(() => {
                document.body.style.animation = "";
            }, 5000); // remove the animation after it has completed one full rotation (5s)

        }
    </script>
</body>

</html>