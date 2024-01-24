<?php
$host = "host = localhost";
$port = "port = 5432";
$dbname = "dbname = postgres";
$user = "user = pineapple";
$password = "password = pineapple";

// Establish a connection
$conn = pg_connect("$host $port $dbname $user $password");

if (!$conn) {
    ?>
    <script>
        alert("Error : Unable to open database");
    </script>
    <?php
}
?>