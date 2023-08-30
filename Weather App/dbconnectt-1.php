<?php
// The connection with MYSQL is established here.
$servername = "localhost";
$username = "newuser";
$password = "password";
$dbname = "weather_api";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_errno) die("<h1>Connection Failed</h1> <p>$connection->connect_error</p>");


?>