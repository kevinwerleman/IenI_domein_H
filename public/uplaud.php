<?php

//---voor errir testen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -------------

$error = "";
$success = "";
$success_data ="";
// if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if($_FILES["upload_csv"]["error"] == 4) {
        $error.="<li>Please select csv file to upload.</li>";
    }else{
        $file_path = pathinfo($_FILES['upload_csv']['name']);
        $file_ext = $file_path['extension'];
        $file_tmp = $_FILES['upload_csv']['tmp_name'];
        $file_size = $_FILES['upload_csv']['size'];	 
        // CSV file extension validation
        if ($file_ext != "csv"){
            $error.="<li>Sorry, only csv file format is allowed.</li>";
          }
        // 1MB file size validation
        if ($file_size > 1048576) {
            $error.="<li>Sorry, maximum 1 MB file size is allowed.</li>";
          }
          $file = fopen($_FILES['upload_csv']['tmp_name'], 'r');

while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
    if (count($row) > 3) {
        $error .= "<li>Sorry, maximaal 3 kolommen toegestaan.</li>";
        break;
    }
}

fclose($file);
        // if(empty($error)){
        //     $file_column = file($file_tmp);
        //     if(count($file_column) > 3){
        //         $error.="<li>Sorry, you can upload maximum 3 rows of data in one go.</li>";
        //    }
        // }
    }
    // if there is no error, then import CSV data into MySQL Database
    if(empty($error)){
		// Include the database connection file 

        $mysqli = new mysqli("127.0.0.1", "user", "password", "klantenberichten_CKT");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $createTableSQL = "
            CREATE TABLE IF NOT EXISTS `recensies` (
                `name` text NOT NULL,
                `email` text NOT NULL,
                `inhoud` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
         if (!$mysqli->query($createTableSQL)) {
            $error .= "<li>Error creating table: " . $mysqli->error . "</li>";
        } else {
            $delimiter = ';'; // Punykomma als scheidingsteken
            $file = fopen($file_tmp, "r");
            $rowNumber = 0;
            while (($row = fgetcsv($file, 0, $delimiter)) !== FALSE) {
                $rowNumber++;
                if (count($row) < 3) {
                    $error .= "<li>Row $rowNumber: not enough columns (found " . count($row) . ", expected at least 3).</li>";
                    continue;
                }
                if ($rowNumber == 1 && isset($_POST['skip_header'])) {
                    continue;
                }
                $name = $mysqli->real_escape_string(trim($row[0]));
                $email = $mysqli->real_escape_string(trim($row[1]));
                $inhoud = $mysqli->real_escape_string(trim($row[2]));
                $query = "INSERT INTO `recensies` (`name`, `email`, `inhoud`) VALUES ('$name', '$email', '$inhoud')";
                if ($mysqli->query($query)) {
                    $success_data .= "<li>" . htmlspecialchars($row[0]) . " - " . htmlspecialchars($row[1]) . " - " . htmlspecialchars($row[2]) . "</li>";
                } else {
                    $error .= "<li>Error inserting row $rowNumber: " . $mysqli->error . "</li>";
                }
            }
            fclose($file);
            if (empty($error)) {
                $success = "Following CSV data was imported successfully.";
            }
        }
        $mysqli->close();
    }
}
?>
<html>
<head>
<title>Demo Import CSV File Data into MySQL Database using PHP - AllPHPTricks.com</title>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
</head>
<body>

<div style="width:700px; margin:50 auto;">
<h1>Demo Import CSV File Data into MySQL Database using PHP</h1>

<?php
if(!empty($error)){
    echo "<div class='alert alert-danger'><ul>";
    echo $error;
    echo "</ul></div>";
	}
if(!empty($success)){
	  echo "<div class='alert alert-success'><h2>".$success."</h2><ul>";
    echo $success_data;
    echo "</ul></div>";
	}
?>

<form method="post" action="" enctype="multipart/form-data">
<input type="file" name="upload_csv" />
<br /><br />
<input type="submit" value="Upload CSV Data"/>
</form>

</div>
</body>
</html>