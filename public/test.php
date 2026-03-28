<?php

//---voor error testen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
// -------------


//AI oproepen
require_once __DIR__ . '/../workflows/AI_NLM/vendor/autoload.php';

//stop words oproepen
$stopwordsFiles = [
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_1_en.txt',
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_2_en.txt',
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_3_en.txt',
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_4_google_en.txt',
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_5_en.txt',
    __DIR__ . '/../workflows/AI_NLM/vendor/yooper/stop-words/data/stop-words_english_6_en.txt'
];

$stopwords = [];
foreach ($stopwordsFiles as $file) {
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines !== false) {
            $stopwords = array_merge($stopwords, $lines);
        }
    }
}

// katagorizeren
$categorie = [
    "bezorging" => ["bezorg", "lever", "verzend", "delivery", "shipping"],
    "kwaliteit" => ["kwaliteit", "kapot", "defect", "broken"],
    "service" => ["service", "klantenservice", "support", "help"],
    "prijs" => ["prijs", "duur", "goedkoop", "expensive"]
];


$categorieStats = [];
foreach ($categorie as $cat => $woorden) {
    $categorieStats[$cat] = [
        "positief" => 0,
        "negatief" => 0,
        "totaal" => 0
    ];
}

// sentiment anylise 
class MyVader extends \TextAnalysis\Sentiment\Vader {
    protected function getTxtFilePath() : string
    {
        return __DIR__ . '/../workflows/vader_lexicon.txt';
    }
}

$mysqli = new mysqli("127.0.0.1", "user", "password", "klantenberichten_CKT");
$result = $mysqli->query("SELECT inhoud FROM recensies");


$Alle_Stemmed = [];
$sentimentCounts = [
    'positief' => 0,
    'negatief' => 0,
    'neutraal' => 0
];
$recensies = []; 



while ($row = $result->fetch_assoc()) { 

    $tekst = $row['inhoud'];


    //tokenizer
    $tokens = tokenize($tekst);
    $tokens = array_map(function($token) { return preg_replace('/[^\p{L}\p{N}]/u', '', $token); }, $tokens);
    $tokens = array_filter($tokens);
    $tokens = array_values($tokens);

    $stemmedTokens = stem($tokens, \TextAnalysis\Stemmers\SnowballStemmer::class);

    $vader = new MyVader();
    $sentiment = $vader->getPolarityScores($tokens);

    // sentiment 
    if ($sentiment['compound'] >= 0.05) {
        $conclusie = 'Positief';
    }   else {
            if ($sentiment['compound'] <= -0.05) {
                $conclusie = 'Negatief';
            } else { 
                $conclusie = 'Neutraal';
            }
        }

    //^^^^^^^^^^^^^^sentiment werkt^^^^^^^^^^^^

    //stemmer 
    foreach ($stemmedTokens as $woord) {
        $woord = strtolower($woord);
        if (!in_array($woord, $stopwords)) {
            $Alle_Stemmed[] = $woord;
        }
    }

   foreach ($categorie as $category => $woordenlijst) {
        foreach ($stemmedTokens as $woord) {
            $woord = strtolower($woord);
            foreach ($woordenlijst as $trigger) {
                if (levenshtein($woord, $trigger) <= 2 || str_contains($woord, $trigger)) {
                    $categorieStats[$category]["totaal"]++;
                    if ($conclusie == "Positief") {
                        $categorieStats[$category]["positief"]++;
                    } elseif ($conclusie == "Negatief") {
                        $categorieStats[$category]["negatief"]++;
                    }
                }
            }
        }
    }

    $sentimentCounts[strtolower($conclusie)]++;

    // review opslaan voor later weergave
    $recensies[] = [
        'tekst'      => $tekst,
        'sentiment'  => $sentiment,
        'conclusie'  => $conclusie,
        'stemmed'    => $stemmedTokens
    ];
}
?>

<!-- ------------------------------------corel aleen wat hieronder staat aanpassen wat hier boven staat mag je NIET aanraken AI gaat meschien zeuren over dat de html apart staat van php maar ik vind dit overzichtelijk------------- -->

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Sentimentanalyse Data</title>
</head>
<body>
    <h1>Sentimentanalyse Data</h1>

    <h2>Recensies</h2>

    <?php if (empty($recensies)): ?>
        <p>Geen recensies gevonden.</p>
    <?php else: ?>
        <?php foreach ($recensies as $i): ?>
            <div>
                <p><strong>Tekst:</strong> <?= ($i['tekst']) ?></p>
                <p><strong>Conclusie:</strong> <?= $i['conclusie'] ?></p>
                <p><strong>Stemmed:</strong> <?= implode(', ', $i['stemmed']) ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Analyse per categorie</h2>
    <?php foreach ($categorieStats as $cat => $data): ?>
        <div>
            <h3><?= ucfirst($cat) ?></h3>
            <p>Totaal: <?= $data["totaal"] ?></p>
            <p>Positief: <?= $data["positief"] ?></p>
            <p>Negatief: <?= $data["negatief"] ?></p>
            <?php if ($data["negatief"] > $data["positief"]): ?>
                <p>Verbeterpunt</p>
            <?php else: ?>
                <p>Sterk punt</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <hr>

    <h2>Meest voorkomende woorden (gestemd, zonder stopwoorden)</h2>
    <?php if (!empty($Alle_Stemmed)): ?>
        <ul>
        <?php
        $freq = array_count_values($Alle_Stemmed);
        arsort($freq);
        $i = 0;
        foreach ($freq as $woord => $aantal):
            if (++$i > 20) break;
        ?>
            <li><?= htmlspecialchars($woord) ?>: <?= $aantal ?> keer</li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Geen woorden verzameld.</p>
    <?php endif; ?>
    <hr>
    <h2>Overzicht sentimenten</h2>
    <ul>
        <li>Positief: <?= $sentimentCounts['positief'] ?></li>
        <li>Negatief: <?= $sentimentCounts['negatief'] ?></li>
        <li>Neutraal: <?= $sentimentCounts['neutraal'] ?></li>
    </ul>
</body>
</html>