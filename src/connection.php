<?php
$servername = "153.92.15.84";
$username = "u148988291_nas_user"; // Change username
$password = "EVALLO21nas"; // Change password
$dbname = "u148988291_nasdb"; // Change database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
else{
    echo("Connected successfully");
}
