<?php

//---voor errir
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//-------------

require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

class MyVader extends \TextAnalysis\Sentiment\Vader {
    protected function getTxtFilePath() : string
    {
        return __DIR__ . '/../storage/sentiment/vader_lexicon/vader_lexicon.txt';
    }
}

//aleen dit aanraken voor database invoegen!!!!!
$mysqli = new mysqli("127.0.0.1", "user", "password", "voornamen");
$result = $mysqli->query("SELECT naam FROM namen");

$tekst = "the product was cool.";
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


<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf8">
</head>
<body>
    <h3>positief of negatief:</h3>
    <p>Tekst: <?= htmlspecialchars($tekst) ?></p>
    <ul>
        <li>Positief: <?= $sentiment['pos'] ?></li>
        <li>Negatief: <?= $sentiment['neg'] ?></li>
        <li>Neutraal: <?= $sentiment['neu'] ?></li>
        <li>Compound: <?= $sentiment['compound'] ?></li>
    </ul>
    <p><strong>Conclusie: <?= ($sentiment['pos'] > $sentiment['neg']) ? 'Positief' : 'Negatief' ?></strong></p>

    <?php  
            while ($row = $result->fetch_assoc()) {
               echo $row['naam'] .  "<br>";
             }
    ?>


</body>
</html>

