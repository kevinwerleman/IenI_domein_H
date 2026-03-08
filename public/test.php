<?php

//---voor errir testen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -------------

require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

$stopwordsFile = __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_1_en.txt';
$stopwords = file($stopwordsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$stopwords = array_map('strtolower', $stopwords);


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


    if ($sentiment['compound'] >= 0.05) {
        $conclusie = 'Positief';
    }   else {
            if ($sentiment['compound'] <= -0.05) {
                $conclusie = 'negatief';
            } else { 
                $conclusie = 'neutraal';
            }
        }

    //^^^^^^^^^^^^^^sentiment werkt^^^^^^^^^^^^

   foreach ($stemmedTokens as $woord) {
        $woord = strtolower($woord);
        if (!in_array($woord, $stopwords)) {
            $Alle_Stemmed[] = $woord;
        }
    }

?>

    <p><strong>Tekst:</strong> <?= $tekst?></p>

    <ul>
        <li>Positief: <?= $sentiment['pos'] ?></li>
        <li>Negatief: <?= $sentiment['neg'] ?></li>
        <li>Neutraal: <?= $sentiment['neu'] ?></li>
        <li>Compound: <?= $sentiment['compound'] ?></li>
    </ul>


    <p><strong>Conclusie:</strong> <?= $conclusie ?></p>

    <p>stemmed stest  <?=  "Stemmed: " . implode(', ', $stemmedTokens); ?> </p>

<?php endwhile; ?>
 
<?php
echo "<p>Alle gestemde woorden (zonder stopwoorden):<p>";
echo "<p>" . print_r(array_count_values($Alle_Stemmed), true) . "</p>";
?>
