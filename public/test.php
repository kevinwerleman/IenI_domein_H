<?php

//---voor errir testen
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// -------------

require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

$stopwordsFile = __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_1_en.txt';

class MyVader extends \TextAnalysis\Sentiment\Vader {
    protected function getTxtFilePath() : string
    {
        return __DIR__ . '/../workflows/AI_NLM/vendor/yooper/php-text-analysis/storage/sentiment/vader_lexicon/vader_lexicon.txt';
    }
}

$mysqli = new mysqli("127.0.0.1", "user", "password", "KLANTENBERICHTEN EIND");
$result = $mysqli->query("SELECT inhoud FROM klantenberichten");

$Alle_Stemmed = [];

?> 


<!doctype html>
<html lang="nl">
<head>  
    <meta charset="utf8">
</head>
<body>

    <h3>positief of negatief:</h3>
    
<?php while ($row = $result->fetch_assoc()): 

    echo $row['inhoud'] .  "<br>"; // mag later wel weg
    $tekst = $row['inhoud'];

    

    // $tekst = "I hate this product!";
    $tokens = tokenize($tekst);
    $tokens = array_map(function($token) { return preg_replace('/[^\p{L}\p{N}]/u', '', $token); }, $tokens);
    $tokens = array_filter($tokens);
    $tokens = array_values($tokens);

    
    
    $stemmedTokens = stem($tokens, \TextAnalysis\Stemmers\SnowballStemmer::class);

    $vader = new MyVader();
    $sentiment = $vader->getPolarityScores($tokens);

?>

<p><strong>Tekst:</strong> <?= htmlspecialchars($tekst) ?></p>

<ul>
    <li>Positief: <?= $sentiment['pos'] ?></li>
    <li>Negatief: <?= $sentiment['neg'] ?></li>
    <li>Neutraal: <?= $sentiment['neu'] ?></li>
    <li>Compound: <?= $sentiment['compound'] ?></li>
</ul>

<!-- berekening voor pos/nue of neg -->
<?php
    if ($sentiment['compound'] >= 0.05) {
        $conclusie = 'Positief';
    }   else {
            if ($sentiment['compound'] <= -0.05) {
                $conclusie = 'negatief';
            } else { 
                $conclusie = 'nuetraal';
            }
        }

    //^^^^^^^^^^^^^^sentiment werkt^^^^^^^^^^^^


foreach ($stemmedTokens as $woord) {
    $woord = strtolower($woord);
    if (!in_array($woord, $stopwords)) {
        $alleStems[] = $woord;
    }
}


?>
<p><strong>Conclusie:</strong> <?= $conclusie ?></p>



<p>stemmed stest  <?=  "Stemmed: " . implode(', ', $stemmedTokens) . " \n"; ?> </p>;


<?php 





?>
    
    <?php endwhile; ?>
<p>stemmed stest  <?=  "Stemmed: " . implode(', ', $stemmedTokens) ; ?> </p>;


</body>
</html>

