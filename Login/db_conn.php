<?php
// the code below is connecting the database using mysqli
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_stage2";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli ->connect_error)
{
    die("Connection failed: " . $mysqli->connect_error);

}
