<?php
$host = "localhost";
$user = "root";
$pass = "root";   // change to "root" only if your MySQL has password
$dbname = "baker_best";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
