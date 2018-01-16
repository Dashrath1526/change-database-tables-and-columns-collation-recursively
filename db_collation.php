<?php
error_reporting(E_ERROR);
$host      = '';                    //host name
$user      = '';                    //your databse username
$pass      = '';                    //your database password
$dbname    = '';                    // databse name
$collation = 'utf8_unicode_ci';

$mysqli = new mysqli($host,$user,$pass,$dbname);
    if(!$mysqli) { 
        echo "Cannot connect to the database ";
        die($mysqli->connect_error);
      }


$mysqli->query("ALTER DATABASE $dbname COLLATE $collation");
$result = $mysqli->query("SHOW TABLES");
while ($row = $result->fetch_row()) {

    $mysqli->query("ALTER TABLE $row[0] COLLATE $collation");
    $result1 = $mysqli->query("SHOW COLUMNS FROM $row[0]");
    while ($row1 = $result1->fetch_assoc()) {
        if (preg_match('~char|text|enum|set~', $row1["Type"])) {
            $mysqli->query("ALTER TABLE $row[0] MODIFY $row1[Field] $row1[Type] COLLATE $collation" . ($row1["Null"] ? "" : " NOT NULL") . ($row1["Default"] && $row1["Default"] != "NULL" ? " DEFAULT '$row1[Default]'" : ""));

            echo 'Table => '.$row[0] .' => Column => '. $row1[Field] .' '. $row1[Type].' <b>. Collation changed.</b><br />';
        }
    }
}

echo "Success.";
?>
