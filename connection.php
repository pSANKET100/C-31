<?php
$host = "host = localhost";
$port = "port = 5432";
$dbname = "dbname = postgres";
$user = "user = postgres";
$password = "password = postgres";

// Establish a connection
$conn = pg_connect("$host $port $dbname $user $password");

if (!$conn) {
    ?>
    <script>
        alert("Error : Unable to open database");
    </script>
    <?php
}
 else {
    echo "Opened database successfully\n";
 }
?>