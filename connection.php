<?php
$host = "localhost";
$port = "5432";
$dbname = "project";
$user = "pineapple";
$password = "pineapple";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    ?>
    <script>
        alert("Error: Unable to open database");
    </script>
    <?php
}
?>