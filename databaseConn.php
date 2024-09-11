<?php
$server = "localhost";
$uname = "root";
$psw = "";
$dbname = "technepal";

// Create connection
$databaseConnection = new mysqli($server, $uname, $psw, $dbname);

// Check connection
if ($databaseConnection->connect_error) {
    die("Connection failed: " . $databaseConnection->connect_error);
}
?>
