<?php
$host = "localhost";
$port = "5432";
$dbname = "postgres";
$user = "sanket";
$password = "sanket";

// Establish a connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
?>
    <script>
        alert("Error: Unable to open database");
    </script>
<?php
}
?>