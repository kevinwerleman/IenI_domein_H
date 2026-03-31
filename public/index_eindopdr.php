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
    "bezorging" => ["delivery", "shipping", "deliv"],
    "kwaliteit" => ["broken", "perform", "work", "reliabl", "qualiti"],
    "service"   => ["service", "support", "help", "servic", "respons"],
    "prijs"     => ["expensive", "price", "cheap"]
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
$alleStemmedWoorden = [];
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

 
    // sentiment 
    $vader = new MyVader();
    $sentiment = $vader->getPolarityScores($tokens);

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

$tokensWithoutStopwords = [];
    foreach ($tokens as $woord) {
        $woord = strtolower($woord);
        if (!in_array($woord, $stopwords)) {
            $tokensWithoutStopwords[] = $woord;
        }
    }

    $stemmedTokens = stem($tokensWithoutStopwords, \TextAnalysis\Stemmers\SnowballStemmer::class);

    $alleStemmedWoorden = array_merge($alleStemmedWoorden, $stemmedTokens);


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

$aantal_recensies = count($recensies);

foreach ($recensies as &$rec) {
    $rec['hoeveelhijd_recensies'] = $aantal_recensies;
}
unset($rec);
?>

<!-- ------------------------------------corel aleen wat hieronder staat aanpassen wat hier boven staat mag je NIET aanraken AI gaat meschien zeuren over dat de html apart staat van php maar ik vind dit overzichtelijk------------- -->

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Sentimentanalyse Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #1f2d3d;
            color: white;
            padding: 20px;
            margin: 0;
            text-align: center;
        }

        h2 {
            margin: 30px 20px 10px;
            border-left: 4px solid #1f2d3d;
            padding-left: 10px;
        }

        .container {
            width: 90%;
            margin: auto;
        }

        .card {
            background: white;
            padding: 15px;
            margin: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            color: white;
            font-size: 12px;
        }

        .positief { background-color: #2ecc71; }
        .negatief { background-color: #e74c3c; }
        .neutraal { background-color: #95a5a6; }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 0 20px;
        }

        .grid .card {
            flex: 1 1 250px;
        }

        ul {
            margin: 15px 40px;
        }

        li {
            margin-bottom: 5px;
        }

        hr {
            margin: 30px 20px;
            border: none;
            border-top: 1px solid #ccc;
        }

        /* NIEUW: grafiek stijl */
        .chart-container {
            margin-top: 10px;
        }

        .chart-bar {
            height: 10px;
            border-radius: 10px;
            margin-bottom: 5px;
        }

        .chart-pos { background-color: #2ecc71; }
        .chart-neg { background-color: #e74c3c; }
        .chart-neu { background-color: #000; }

        .toggle-btn {
            margin: 20px;
            padding: 12px 20px;
            background-color: #4da3ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .toggle-btn:hover {
            background-color: #3b8edb;
        }
    </style>
</head>
<body>

<div class="menu">
    <div class="menu-container">
        <div class="logo">Insight AI</div>
        <div class="links">
            <a href="Homepagina.html">Home</a>
            <a href="Functionaliteiten.html">Functionaliteiten</a>
            <a href="uplaud.php">Upload</a>
            <a href="Contact.html">Contact</a>
        </div>
    </div>
</div>

<style>
.menu {
    background-color: #1e2a38;
    padding: 15px 0;
}

.menu-container {
    width: 90%;
    max-width: 1100px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: white;
    font-weight: bold;
    font-size: 20px;
    letter-spacing: 1px;
}

.links a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
    font-size: 14px;
    padding: 8px 14px;
    border-radius: 6px;
    transition: 0.3s;
    background-color: rgba(255,255,255,0.1);
}

.links a:hover {
    background-color: #4da3ff;
    color: white;
    transform: translateY(-2px);
}
</style>

<div class="container">

    <h2>Analyse per categorie</h2>

    <div class="grid">
        <?php foreach ($categorieStats as $cat => $a): ?>
            <div class="card">
                <h3><?= ($cat) ?></h3>
                <p>Positief: <?= $a["positief"] ?></p>
                <p>Negatief: <?= $a["negatief"] ?></p>
                <p>Neutraal: <?= $a["totaal"] - $a["positief"] - $a["negatief"] ?></p>

                <?php if ($a["negatief"] > $a["positief"]): ?>
                    <p class="badge negatief">Verbeterpunt</p>
                <?php else: ?>
                    <p class="badge positief">Sterk punt</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <h2>Overzicht sentimenten</h2>

    <ul>
        <li><span class="badge positief">Positief: <?= $sentimentCounts['positief'] ?></span></li>
        <li><span class="badge negatief">Negatief: <?= $sentimentCounts['negatief'] ?></span></li>
        <li><span class="badge neutraal">Neutraal: <?= $sentimentCounts['neutraal'] ?></span></li>
    </ul>

    <hr>

    <button class="toggle-btn" onclick="toggleRecensies()">Toon recensies</button>

    <div id="recensies-blok" style="display:none;">

        <h2>Recensies</h2>

        <?php if (empty($recensies)): ?>
            <p style="margin:20px;">Geen recensies gevonden.</p>
        <?php else: ?>
            <?php foreach ($recensies as $i): ?>
                <div class="card">
                    <p><strong>Naam:</strong> <?= ($i['naam']) ?></p>
                    <p><strong>E-mail:</strong> <?= ($i['email']) ?></p>
                    <p><strong>Inhoud:</strong> <?= ($i['inhoud']) ?></p>
                    <p>
                        <strong>Conclusie:</strong> 
                        <span class="badge <?= strtolower($i['conclusie']) ?>">
                            <?= $i['conclusie'] ?>
                        </span>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>

<script>
// toggle recensies
function toggleRecensies() {
    var blok = document.getElementById("recensies-blok");
    if (blok.style.display === "none") {
        blok.style.display = "block";
    } else {
        blok.style.display = "none";
    }
}

// automatische grafieken genereren
document.querySelectorAll(".grid .card").forEach(card => {

    let tekst = card.innerText;

    let pos = parseInt((tekst.match(/Positief:\s*(\d+)/) || [])[1]) || 0;
    let neg = parseInt((tekst.match(/Negatief:\s*(\d+)/) || [])[1]) || 0;
    let neu = parseInt((tekst.match(/Neutraal:\s*(\d+)/) || [])[1]) || 0;

    let totaal = pos + neg + neu;
    if (totaal === 0) return;

    let posP = (pos / totaal) * 100;
    let negP = (neg / totaal) * 100;
    let neuP = (neu / totaal) * 100;

    let container = document.createElement("div");
    container.className = "chart-container";

    let barPos = document.createElement("div");
    barPos.className = "chart-bar chart-pos";
    barPos.style.width = posP + "%";

    let barNeg = document.createElement("div");
    barNeg.className = "chart-bar chart-neg";
    barNeg.style.width = negP + "%";

    let barNeu = document.createElement("div");
    barNeu.className = "chart-bar chart-neu";
    barNeu.style.width = neuP + "%";

    container.appendChild(barPos);
    container.appendChild(barNeg);
    container.appendChild(barNeu);

    card.appendChild(container);
});
</script>

</body>
</html>