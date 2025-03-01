<?php
// the code below is connecting the database using PDO

$host = 'localhost';
$dbname = 'my_stage2';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


