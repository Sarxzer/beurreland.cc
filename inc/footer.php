        <footer class="footer">
            <p><strong>© 2026 Dieu du Beurre</strong> - Tous beurres réservés</p>
            <p><strong>© 2026 Sarxzer</strong> - Tous beurres réservés</p>
            <p><strong>Contact officiel :</strong> <a href="mailto:frescri@beurreland.cc">frescri@beurreland.cc</a></p>
            <p><strong>Webmaster :</strong> <a href="mailto:sarxzer@sarxzer.xyz">sarxzer@sarxzer.xyz</a></p>
            <p class="small">
                Dernière mise à jour : <?= date("d/m/Y à H:i", filemtime($current_file)) ?> • Culte sacré en construction permanente • Hébergé avec amour,
                beurre et par un des serviteur du culte, <span class="tooltip">Sarxzer<span class="tooltiptext">Webmaster du culte</span></span>.
            </p>
        </footer>
        <?php
        // send an update to the counter api to increment the counter for each page load

        require __DIR__ . '/../vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();


        // send a POST request to the counter api to increment the counter for each page load
        // api key need to be in x-auth-token header for authentication

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://beurreland.cc/api/v1/counter.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Auth-Token: ' . $_ENV['AUTH_TOKEN']]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        ?>