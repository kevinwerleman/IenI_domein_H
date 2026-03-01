<?php

//---voor errir testen
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// -------------

require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

class MyVader extends \TextAnalysis\Sentiment\Vader {
    protected function getTxtFilePath() : string
    {
        return __DIR__ . '/../vaderSentiment-3.3.2/vaderSentiment/vader_lexicon.txt';
    }
}

$mysqli = new mysqli("127.0.0.1", "user", "password", "KLANTENBERICHTEN EIND");
$result = $mysqli->query("SELECT inhoud FROM klantenberichten");

?>


<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf8">
</head>
<body>

    <h3>positief of negatief:</h3>
    
<?php
while ($row = $result->fetch_assoc()): 

    echo $row['inhoud'] .  "<br>";
    //$tekst = $row['inhoud'];

    $tekst = "I love this product!";
    $tokens = tokenize($tekst);
    //!!!!!!!!!!!!!!!!!!


    $tokens = array_map(function($token) {
    return preg_replace('/[^\p{L}\p{N}]/u', '', $token);
    }, $tokens);
    $tokens = array_filter($tokens);
    $tokens = array_values($tokens);

    $vader = new MyVader();
    $sentiment = $vader->getPolarityScores($tokens);

?>

        <div class="bericht">
            <p><strong>Tekst:</strong> <?= htmlspecialchars($tekst) ?></p>
            <ul class="scores">
                <li>Positief: <?= $sentiment['pos'] ?></li>
                <li>Negatief: <?= $sentiment['neg'] ?></li>
                <li>Neutraal: <?= $sentiment['neu'] ?></li>
                <li>Compound: <?= $sentiment['compound'] ?></li>
            </ul>
            <p><strong>Conclusie:</strong> 
                <?= ($sentiment['pos'] > $sentiment['neg']) ? 'Positief' : 'Negatief' ?>
            </p>
        </div>
        
    <?php endwhile; ?>
</body>
</html>

