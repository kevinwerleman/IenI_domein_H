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
$result = $mysqli->query("SELECT * FROM recensies");


$Alle_Tokens = [];
$sentimentCounts = [
    'positief' => 0,
    'negatief' => 0,
    'neutraal' => 0
];

$recensies = []; 

$aantal_resensies = count($recensies); 


while ($row = $result->fetch_assoc()) { 

    $inhoud = $row['inhoud'];
    $name  = $row['name'];
    $email = $row['email'];



    //tokenizer
    $tokens = tokenize($inhoud);
    $tokens = array_map(function($token) { return preg_replace('/[^\p{L}\p{N}]/u', '', $token); }, $tokens);
    $tokens = array_filter($tokens);
    $tokens = array_values($tokens);
    $aantal_tokens = count($tokens);


    //$stemmedTokens = stem($tokens, \TextAnalysis\Stemmers\SnowballStemmer::class);

    $vader = new MyVader();
    $sentiment = $vader->getPolarityScores($tokens);

    // sentiment 

    $sentiment_neg  = $sentiment['neg'];
    $sentiment_pos  = $sentiment['pos'];
    $sentiment_neu  = $sentiment['neu'];
    $sentiment_comp = $sentiment['compound']; 

    if ($sentiment_comp >= 0.05) {
        $conclusie = 'Positief';
    }   else {
            if ($sentiment_comp <= -0.05) {
                $conclusie = 'Negatief';
            } else { 
                $conclusie = 'Neutraal';
            }
        }

    //^^^^^^^^^^^^^^sentiment werkt^^^^^^^^^^^^

    //stemmer 
    foreach ($tokens as $woord) {
        $woord = strtolower($woord);
        if (!in_array($woord, $stopwords)) {
            $Alle_Tokens[] = $woord;
        }
    }

$stemmedTokens = stem($Alle_Tokens, \TextAnalysis\Stemmers\SnowballStemmer::class);

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

// voor weergaven
        $recensies[] = [
        'inhoud'     => $inhoud,
        'naam'       => $name,
        'email'      => $email,
        'mening_score_neg'  => $sentiment_neg,  // de negatiefe waarden die een recensie heeft. 
        'mening_score_pos'  => $sentiment_pos,  // de positiefe waarden die een recensie heeft. 
        'mening_score_neu'  => $sentiment_neu,  // de neutaale waarden die een recensie heeft.
        'mening_score_tot'  => $sentiment_comp, // de totaal berekende waarden die een recentie heeft in getallen. 
        'conclusie'  => $conclusie, // of een woord positief, negatief of neuraal is.
        'stemmed'    => $stemmedTokens, // woorden na stopwords en fout gesplelt. 
        'tokens'     => $tokens, // alle woorden in recensie.
        'aantal_tokens_in_zin'  => $aantal_tokens, // hoeveelhijd woorden in de recensie.
        'hoeveelhijd_recensies' => $aantal_resensies, // aantaal geplaatste recensies.
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
                <p><strong>Naam:</strong> <?= ($i['naam']) ?></p>
                <p><strong>E‑mail:</strong> <?= ($i['email']) ?></p>
                <p><strong>Inhoud:</strong> <?= ($i['inhoud']) ?></p>
                <p><strong>Conclusie:</strong> <?= $i['conclusie'] ?></p>
                <!-- <p><strong>Stemmed:</strong> <?= implode(', ', $i['stemmed']) ?></p> -->
                <p><strong>Tokens:</strong> <?= implode(', ', $i['tokens']) ?></p>
                <p><strong>woorden in zin:</strong> <?= ($i['aantal_tokens_in_zin']) ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Analyse per categorie</h2>
    <?php foreach ($categorieStats as $cat => $a): ?>
        <div>
            
            <h3><?= ($cat) ?></h3>
                <p>Totaal: <?= $a["totaal"] ?></p>
                <p>Positief: <?= $a["positief"] ?></p>
                <p>Negatief: <?= $a["negatief"] ?></p>

            <?php if ($a["negatief"] > $a["positief"]): ?>
                <p>Verbeterpunt</p>
            <?php else: ?>
                <p>Sterk punt</p>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
    <hr>

    <h2>Meest voorkomende woorden (gestemd, zonder stopwoorden)</h2>
    <?php if (!empty($recensies)): ?>
        <ul>
        <?php
        $freq = array_count_values(['stemmed']);
        arsort($freq);
        $i = 0;
        foreach ($freq as $woord => $aantal):
            if (++$i > 20) break;
        ?>
            <li><?= ($woord) ?>: <?= $aantal ?> keer</li>
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