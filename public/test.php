<?php

//---voor error testen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -------------

require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

$stopwordsFile = __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_1_en.txt';
$stopwords = file($stopwordsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$stopwords = array_map('strtolower', $stopwords);

$categories = [
    "bezorging" => ["bezorg", "lever", "verzend", "delivery", "shipping"],
    "kwaliteit" => ["kwaliteit", "kapot", "defect", "broken"],
    "service" => ["service", "klantenservice", "support", "help"],
    "prijs" => ["prijs", "duur", "goedkoop", "expensive"]
];

$categoryStats = [];
foreach ($categories as $cat => $woorden) {
    $categoryStats[$cat] = [
        "positief" => 0,
        "negatief" => 0,
        "totaal" => 0
    ];
}

class MyVader extends \TextAnalysis\Sentiment\Vader {
    protected function getTxtFilePath() : string
    {
        return __DIR__ . '/../workflows/AI_NLM/vendor/yooper/php-text-analysis/storage/sentiment/vader_lexicon/vader_lexicon.txt';
    }
}

$mysqli = new mysqli("127.0.0.1", "user", "password", "klantenberichten_CKT");
$result = $mysqli->query("SELECT inhoud FROM klantenberichten");

$Alle_Stemmed = [];

?>

<?php while ($row = $result->fetch_assoc()): 

    echo $row['inhoud'] .  "<br>"; // mag later wel weg
    $tekst = $row['inhoud'];

    echo "<hr>";
    echo "<p><strong>Tekst:</strong> $tekst</p>";

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

    foreach ($categories as $category => $woordenlijst) {
        foreach ($stemmedTokens as $woord) {
            $woord = strtolower($woord);
            foreach ($woordenlijst as $trigger) {
                if (levenshtein($woord, $trigger) <= 2 || str_contains($woord, $trigger)) {
                    $categoryStats[$category]["totaal"]++;
                    if ($conclusie == "Positief") {
                        $categoryStats[$category]["positief"]++;
                    } elseif ($conclusie == "Negatief") {
                        $categoryStats[$category]["negatief"]++;
                    }
                }
            }
        }
    }

?>

<ul>
    <li>Positief: <?= $sentiment['pos'] ?></li>
    <li>Negatief: <?= $sentiment['neg'] ?></li>
    <li>Neutraal: <?= $sentiment['neu'] ?></li>
    <li>Compound: <?= $sentiment['compound'] ?></li>
</ul>

    <p><strong>Conclusie:</strong> <?= $conclusie ?></p>

    <p><strong>Stemmed:</strong> <?= implode(', ', $stemmedTokens); ?></p>

<?php endwhile; ?>


<?php

echo "<hr><h2>Analyse per categorie</h2>";

foreach ($categoryStats as $cat => $data) {

    echo "<div style='margin-bottom:20px; padding:15px; background:white; border-radius:10px;'>";

    echo "<h3>" . ucfirst($cat) . "</h3>";

    echo "<p>Totaal: " . $data["totaal"] . "</p>";
    echo "<p>Positief: " . $data["positief"] . "</p>";
    echo "<p>Negatief: " . $data["negatief"] . "</p>";

    if ($data["negatief"] > $data["positief"]) {
        echo "<strong style='color:red'>Verbeterpunt</strong>";
    } else {
        echo "<strong style='color:green'>Sterk punt</strong>";
    }

    echo "</div>";
}

echo "<hr><h2>bannanen</h2>";
print_r(array_count_values($Alle_Stemmed));

?>
