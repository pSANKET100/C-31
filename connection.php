<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$host = "aws-0-ap-south-1.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.ckdqlddwsijbhxzcknxn";
$password = "60FGSqefuewNQk7F";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    $error_message = pg_last_error(); // Deprecated function, but still useful for now
    echo "Error: Unable to connect to the database. Details: $error_message";
}
