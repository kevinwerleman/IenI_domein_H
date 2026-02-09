<?php
// echo "Hallo";
//PHPinfo();

$mysqli = new mysqli("127.0.0.1", "user", "password","mobiel");

$result = $mysqli->query("SELECT id FROM aankopen");

?>

<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf8">
</head>
         <?php  
            while ($row = $result->fetch_assoc()) {
               echo $row['id'] .  "<br>";
             }
         ?>
</body>
</html>