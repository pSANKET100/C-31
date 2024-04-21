<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$host = "localhost";
$port = "5432";
$dbname = "project";
$user = "pineapple";
$password = "pineapple";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    $error_message = pg_last_error(); // Deprecated function, but still useful for now
    echo "Error: Unable to connect to the database. Details: $error_message";
}
