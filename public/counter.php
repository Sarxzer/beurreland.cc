<?php
$file = '/var/www/data/counter.txt';

$fp = fopen($file, 'c+');

if (!$fp) {
    http_response_code(500);
    exit(include "500.php");
}

flock($fp, LOCK_EX);

$counter = (int)trim(stream_get_contents($fp));

if ($_GET["view"]) {
    echo $counter;
} elseif ($_GET["increment"]) {
    $counter++;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $counter);
    echo $counter;
} else {
    echo "
<h1>count <span id=\"counter\"></span></h1>

<script>
    function viewCounter() {
        fetch('?view=1')
            .then(response => response.text())
            .then(data => {
                document.getElementById('counter').innerText = data;
            });
    }

    // Show the counter value every 0.5 seconds
    setInterval(viewCounter, 500);
</script>";
}


flock($fp, LOCK_UN);
fclose($fp);
?>