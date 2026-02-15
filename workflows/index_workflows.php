<?php
// echo "Hallo";
//PHPinfo();

$mysqli = new mysqli("127.0.0.1", "user", "password","voornamen");

$result = $mysqli->query("SELECT naam FROM namen");

?>

<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf8">
</head>
         <?php  
            while ($row = $result->fetch_assoc()) {
               echo $row['naam'] .  "<br>";
             }
         ?>
</body>
</html>