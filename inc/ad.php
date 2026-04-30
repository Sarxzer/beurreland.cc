<figure class="image" id="ad">
    <?php
    // $ads = [
    //     "/assets/img/Frescri-Beurre-Pub.png",
    //     "/assets/img/beurre.jpg",
    //     "/assets/img/Beurre-Exquis-Ad.png"
    // ];

    // $ads_alt = [
    //     "Publicité pour le Beurre Gastronomique de Frescri",
    //     "Publicité pour le Beurre Deluxe de Frescri",
    //     "Publicité pour le Beurre Exquis de Frescri"
    // ];

    //dict or array of ads and their alt texts
    $ads = [
        "/assets/img/Frescri-Beurre-Pub.png" => "Publicité pour le Beurre Gastronomique de Frescri",
        "/assets/img/beurre.jpg" => "beurre.jpg",
        "/assets/img/beurre-frescri-ad.png" => "Publicité pour le Beurre Deluxe de Frescri",
        "/assets/img/davide-jambon-beuere.gif" => "Désolé, le dieu du beurre a mangé la pub"
    ];

    // Select a random ad
    $randomAd = array_rand($ads);
    $randomAdAlt = $ads[$randomAd];
    ?>
    <img src="<?php echo $randomAd; ?>" alt="<?php echo $randomAdAlt; ?>">
    <figcaption><?php echo $randomAdAlt; ?></figcaption>
</figure>