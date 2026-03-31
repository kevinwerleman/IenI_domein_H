<?php

?>
<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="data:,">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <title>Databases Omgeving</title>
</head>

<body class="container" style="background-color: rgba(0, 0, 0, 0.1);">
    <main class="mx-5 p-5">
        <div class="container mx-3 my-2 shadow p-5 bg-body rounded">
            <h1 class="fw-bold text-center mb-4">
                <i class="bi bi-database"></i>
                Domein H Databases (v0.7)
            </h1>
            <div class="row">
                <div class="col m-1 px-5 py-1">
                    <div class="mb-5">
                        <h2>
                            <i class="bi bi-book"></i>
                            Lesmateriaal
                        </h2>

                        <?php
                        $files = scandir("../lesmateriaal/public");
                        $ignored_files = [".", ".."];
                        echo "<ul>";
                        foreach ($files as $file) {
                            if (!in_array($file, $ignored_files)) {
                                echo '<li><a title="' . $file . '" href="/lesmateriaal/' . $file . '" target="_blank"><strong>' . $file . '</strong></a></li>';
                            }
                        }
                        ?>
                    </div>
                    <div>
                        <h2>
                            <i class="bi bi-list-ul"></i>
                            Websites
                        </h2>
                        <?php
                        $files = scandir("./");
                        $ignored_files = [".", "..", "lesmateriaal"];
                        $found = false;
                        echo "<ul>";

echo '<li><a title="Home" href="Homepagina.html"><strong>homepagina inside AI</strong></a></li>';

                        foreach ($files as $file) {
                            if (is_dir($file) && !in_array($file, $ignored_files)) {
                                echo '<li><a title="' . $file . '" href="/' . $file . '" target="_blank"><strong>' . $file . '</strong></a></li>';
                                $found = true;
                            }
                        }
                        echo "</ul>";
                        if (!$found) {
                            echo '<p><small>Nog geen websites gevonden, maak een nieuwe website door een map onder \'public\' aan te maken.</small></p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="col m-2 px-5 py-1">
                    <div class="mb-5">
                        <h2>
                            <i class="bi bi-sliders2-vertical"></i>
                            Database beheer
                        </h2>
                        <div id="loading" class="alert alert-info" style="display: none;" role="alert">
                            <div class="d-flex align-items-center">
                                <p id="loading-msg">Aan het laden..</p>
                                <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                            </div>
                        </div>
                        <?php
                        function info($msg)
                        {
                            echo '<div id="result-msg" class="alert alert-success" role="alert">' . $msg . '</div>';
                        }
                        function error($msg)
                        {
                            echo '<div id="result-msg" class="alert alert-danger" role="alert">' . $msg . '</div>';
                        }
                        function runScript($fileName)
                        {
                            $cmd = "cd .. && sh ./setup/" . $fileName;
                            shell_exec($cmd);
                        }

                        if ($_POST['import'] == 1) {
                            runScript("import-dbs.sh");
                            info("Databases geïmporteerd.");
                        }

                        if ($_POST['export'] == 1) {
                            runScript("export-dbs.sh");
                            info("Databases gëexporteerd.");
                        }

                        if ($_POST['restore_default'] == 1) {
                            runScript("restore-default-dbs.sh");
                            info("Standaard databases hersteld.");
                        }

                        $mysqli = new mysqli("127.0.0.1", "user", "password");
                        if ($result = $mysqli->query("SHOW databases")) {
                            $databases = array();
                            while ($row = $result->fetch_row()) {
                                $dbName = $row[0];
                                // Filter out internal databases
                                if ($dbName != "information_schema" && $dbName != "performance_schema" && $dbName != "sys" && $dbName != "mysql") {
                                    $databases[] = $dbName;
                                }
                            }
                            $result->free_result();
                        }
                        echo "<b>Er zijn " . count($databases) . " databases beschikbaar in MySQL: </b>";
                        echo "<ul>";
                        foreach ($databases as $dbName) {
                            $isDefault = file_exists("../lesmateriaal/standaard-databases/" . $dbName . ".sql");
                            echo "<li>" . $dbName;
                            if ($isDefault) {
                                echo ' <small class="text-muted">(standaard)</small>';
                            }
                            echo "</li>";
                        }
                        echo "</ul>";
                        ?>
                        <form id="db-man-form" action="index.php" method="post">
                            <div class="btn-group mb-2" role="group">
                                <button class="btn btn-outline-primary" name="export" value="1" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Exporteer</b> de databases naar de databases map.">
                                    <i class="bi bi-arrow-bar-up"></i>
                                    Exporteren</button>
                                <button class="btn btn-outline-primary" name="import" value="1" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Importeer</b> de databases uit de databases map.">
                                    <i class="bi bi-arrow-bar-down"></i>
                                    Importeren</button>
                                <a href="/phpmyadmin/" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-html="true" title="Open <b>phpMyAdmin</b> voor het verdere beheer van MySQL." target="_blank">
                                    <i class="bi bi-database-fill-gear"></i>
                                    phpMyAdmin
                                </a>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" name="restore_default" value="1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<b>Herstel</b> de databases die bij het lesmateriaal horen.">
                                <i class="bi bi-skip-backward"></i>
                                Herstel standaard databases</button>
                        </form>
                        <script>
                            document.getElementById("db-man-form").addEventListener('submit', () => {
                                document.getElementById("loading").style.display = "";
                                if (document.getElementById("result-msg") != null) {
                                    document.getElementById("result-msg").style.display = "none";
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <footer class="d-flex flex-wrap align-items-center justify-content-between py-3 my-4 mx-5">
                <span class="text-muted">Made by Rijk van Putten</span>
                <div>
                    <?php
                    echo '<span class="text-muted mx-3">PHP: <strong>' . phpversion() . '</strong></span>';
                    $mysql_v = (float)$mysqli->server_version;
                    $main_version = floor($mysql_v / 10000);
                    $minor_version = floor(($mysql_v - $main_version * 10000) / 100);
                    $sub_version = $mysql_v - $main_version * 10000 - $minor_version * 100;
                    echo '<span class="text-muted mx-3">MySQL: <strong>' . $main_version . "." . $minor_version . "." . $sub_version . '</strong></span>';

                    $git_hash = shell_exec("git rev-parse HEAD");
                    if (isset($git_hash)) {
                        echo '<span class="text-muted mx-3">Commit: <strong>' . substr($git_hash, 0, 7) . '</strong></span>';
                    }
                    ?>
                </div>
            </footer>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        // Add bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        // Automatic dark mode for Bootstrap
        function updateTheme() {
            const colorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            document.querySelector("html").setAttribute("data-bs-theme", colorMode);
        }
        updateTheme()
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateTheme);
    </script>
    </script>
</body>

</html>