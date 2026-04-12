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
                data.slice(0, 10).forEach(commit => {
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


        async function getCommitCount(owner, repo) {
            const url = `https://api.github.com/repos/${owner}/${repo}/commits?per_page=1`;

            const res = await fetch(url);
            const link = res.headers.get("link");

            if (!link) {
                // repo has <= 1 commit
                const data = await res.json();
                return data.length;
            }

            // extract last page number
            const match = link.match(/&page=(\d+)>;\s*rel="last"/);
            return match ? parseInt(match[1]) : 1;
        }

        getCommitCount('Sarxzer', 'Beurreland.cc')
            .then(count => {
                document.getElementById('commit-count').innerHTML = `<strong>Nombre de commits :</strong> ${count}`;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération du nombre de commits :', error);
                document.getElementById('commit-count').innerHTML = '<strong>Impossible de récupérer le nombre de commits.</strong>';
            });
    </script>
</body>

</html>