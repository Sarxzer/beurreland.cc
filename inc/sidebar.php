<div class="sidebar">
        <h1><a href="?jambon=beurre">
                <marquee>Beurreland</marquee>
            </a></h1>
        <div class="counter-container">
            <h3>Voici le compteur de visites :</h3>
            <div class="counter" id="counter">
                <?php
                // update the counter value in the database and get the current value
                $stmt = $pdo->prepare('UPDATE counters SET value = value + 1 WHERE name = "visits"');
                $stmt->execute();

                $stmt = $pdo->prepare('SELECT value FROM counters WHERE name = "visits"');
                $stmt->execute();
                $counterValue = (int)$stmt->fetchColumn();

                // display the counter value with each digit in 6 separate div if not empty
                if ($counterValue > 0) {
                    // ensure we have exactly 6 digits by padding with leading zeros
                    $counterValue = str_pad($counterValue, 6, '0', STR_PAD_LEFT);
                    $digits = str_split($counterValue);
                    foreach ($digits as $digit) {
                        echo '<div class="digit">' . $digit . '</div>';
                    }
                } else {
                    echo '<div class="digit">0</div>';
                }
                ?>
            </div>
        </div>

        <div class="navlinks">
            <a href="/">Accueil</a>
            <!-- <a href="/404">Page 404</a>
            <a href="/403">Page 403</a>
            <a href="/429">Page 429</a>
            <a href="/500">Page 500</a> -->
            <a href="/contact">Contact</a>
            <a href="/guestbook">Guestbook</a>
            <a href="/github">Github</a>
            <a href="/panel">Panel</a>
        </div>

        
    </div>