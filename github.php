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
</head>

<body>
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
        <div class="banner">
            <h1>Github Info</h1>
            <div class="subtitle">Informations sur le projet Beurreland sur Github</div>
        </div>

        <div class="content">
            <div id="latest-commit">Chargement...</div>
            <br>
            <br>
            <div id="commits"></div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <script src="/src/js/script.js"></script>
    <script>
        fetch('https://api.github.com/repos/Sarxzer/Beurreland.cc/commits')
            .then(response => response.json())
            .then(data => {
                const latestCommit = data[0];
                const commitSha = latestCommit.sha;
                const commitSmallSha = commitSha.substring(0, 7);
                const commitUrl = latestCommit.html_url;
                const commitMessage = latestCommit.commit.message;
                const commitAuthor = latestCommit.commit.author.name;
                const commitDate = new Date(latestCommit.commit.author.date).toLocaleString();

                document.getElementById('latest-commit').innerHTML = `
                    <h2>Dernier commit</h2>
                    <p><strong>SHA :</strong> <a href="${commitUrl}" target="_blank">${commitSmallSha}</a></p>
                    <p><strong>Message :</strong> ${commitMessage}</p>
                    <p><strong>Auteur :</strong> ${commitAuthor}</p>
                    <p><strong>Date :</strong> ${commitDate}</p>
                `;

                const commitsContainer = document.getElementById('commits');
                commitsContainer.innerHTML = '<h2>Commits récents</h2>';
                data.slice(0, 5).forEach(commit => {
                    const sha = commit.sha.substring(0, 7);
                    const url = commit.html_url;
                    const message = commit.commit.message;
                    const author = commit.commit.author.name;
                    const date = new Date(commit.commit.author.date).toLocaleString();

                    const commitElement = document.createElement('div');
                    commitElement.classList.add('commit');
                    commitElement.innerHTML = `
                        <p><strong>SHA :</strong> <a href="${url}" target="_blank">${sha}</a></p>
                        <p><strong>Message :</strong> ${message}</p>
                        <p><strong>Auteur :</strong> ${author}</p>
                        <p><strong>Date :</strong> ${date}</p>
                        <hr>
                    `;
                    commitsContainer.appendChild(commitElement);
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des commits :', error);
                document.getElementById('latest-commit').textContent = 'Impossible de récupérer les informations du commit.';
            });
    </script>
</body>

</html>