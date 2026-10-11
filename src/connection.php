<?php

$servername = "127.0.0.1:3306";
$username = "root";
$password = "";
$dbname = "async_db";

$mysqli = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);

/* Check connection first */
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

/* Set MySQL timezone to Philippine Time */
if (!$mysqli->query("SET time_zone = '+08:00'")) {
    die("Timezone configuration failed: " . $mysqli->error);
}
